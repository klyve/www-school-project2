import argparse
from subprocess import call
import os
import sys
from dotenv import load_dotenv

def change_working_directory_to_script_directory():
    scriptpath = os.path.dirname(os.path.realpath(__file__))
    os.chdir(scriptpath)
    return scriptpath

def get_script_directory():
    return os.path.dirname(os.path.realpath(__file__))

def load_environment_file():
    ROOT = get_script_directory()
    load_dotenv(dotenv_path=ROOT+"/.env", verbose=True)

def setdevenv():

    def writeOrDefault(file, key, default):

        displayText = "{}: ({}) ".format(key, default)
        param = input(displayText)
        if not param:
            file.write("export "+key+"="+default+"\n")
            file.write("echo "+key+": $"+key+"\n")
            return default

        file.write("export "+key+"="+param+"\n")    
        file.write("echo "+key+": $"+key+"\n")
        return param


    ROOT = get_script_directory()
    with open(ROOT+"/.env", "w") as envfile:

        envfile.write("DIR=\"$( cd \"$( dirname \"${BASH_SOURCE[0]}\" )\" && pwd )\"\n")
        envfile.write("alias krustool=\"python3 $DIR/cmd.py\"\n")
        envfile.write("echo \"aliased python3 $DIR/cmd.py -> krustool\"\n\n")
        envfile.write("export KRUS_ROOT=$DIR\n")


        writeOrDefault(envfile,"KRUS_WEB_HOST", "127.0.0.1")
        writeOrDefault(envfile,"KRUS_WEB_PORT", "8080")
        apihost = writeOrDefault(envfile, "KRUS_API_HOST", "127.0.0.1")
        apiport  = writeOrDefault(envfile,"KRUS_API_PORT", "4000")
        dbhost = writeOrDefault(envfile,"KRUS_DB_HOST", "127.0.0.1")
        dbport = writeOrDefault(envfile,"KRUS_DB_PORT", "3306")
        dbname = writeOrDefault(envfile,"KRUS_DB_NAME", "mvc")
        dbuser = writeOrDefault(envfile,"KRUS_DB_USER", "root")
        dbpassword = writeOrDefault(envfile,"KRUS_DB_PASSWORD", "")

        with open(ROOT+"/apitests/.env", "w") as apienv:
            apienv.write("KRUS_API_HOST="+apihost+"\n")
            apienv.write("KRUS_API_PORT="+apiport+"\n")

        with open(ROOT+"/server/src/App/.env", "w") as serverenv:
            serverenv.write("KRUS_DB_HOST="+dbhost+"\n")
            serverenv.write("KRUS_DB_PORT="+dbport+"\n")
            serverenv.write("KRUS_DB_NAME="+dbname+"\n")
            serverenv.write("KRUS_DB_USER="+dbuser+"\n")
            serverenv.write("KRUS_DB_PASSWORD="+dbpassword+"\n")


def fetch():

    ROOT = get_script_directory()

    os.chdir( ROOT+"/apitests" ) 
    call(["npm","install"])
    
    os.chdir("../server")
    call(["composer","install"])
    
    os.chdir("../design")
    call(["bower","install"])
    call(["npm","install"])

    os.chdir("../app")
    call(["bower","install"])
    call(["npm","install"])

    os.chdir("..")


import zipfile
# ref https://stackoverflow.com/a/1855118/9636402 02.05.2018
def zip_distribution(version):
    def zipdir(path, ziph):
        for root, dirs, files in os.walk(path):
            for file in files:
                ziph.write(os.path.join(root, file))

    ROOT = get_script_directory()

    zipf = zipfile.ZipFile('kruskontroll-'+version+'.zip', 'w', zipfile.ZIP_DEFLATED)
    zipdir('dist/', zipf)
    zipf.close()

    call(["rm", "-r", ROOT+"/dist"])

def build_only():

    ROOT = get_script_directory()

    call(["rm", "-r", ROOT+"/dist"])
    call(["mkdir", "dist"])

    call(["cp", "-r", ROOT+"/server/public", ROOT+"/dist/public"])
    call(["cp", "-r", ROOT+"/server/install", ROOT+"/dist/install"])
    call(["cp", "-r", ROOT+"/server/src", ROOT+"/dist/src"])
    call(["cp", "-r", ROOT+"/server/vendor", ROOT+"/dist/vendor"])
    call(["cp", ROOT+"/server/index.php", ROOT+"/dist/index.php"])

    call(["rm", ROOT+"/dist/src/App/.env"])


def build(version):
    fetch()
    build_only()
    zip_distribution(version)


def migseed():
    call(["php", "server/toolbox", "migrate:refresh"])
    call(["php", "server/toolbox", "seed:refresh"])

def test(path):
    call(["php", "server/toolbox", "migrate:refresh"])
    call(["php", "server/toolbox", "seed:refresh"])
    call(["ava", "--fail-fast", "--verbose", "--watch", path])


def test_all():
    call(["php", "server/toolbox", "migrate:refresh"])
    call(["php", "server/toolbox", "seed:refresh"])

    call(["ava", "--fail-fast", "--verbose", "--watch", "apitests/tests/"])


def listenv():
    print("KRUS_ROOT:",     os.environ.get("KRUS_ROOT"))
    print("KRUS_WEB_HOST:", os.environ.get("KRUS_WEB_HOST"))
    print("KRUS_WEB_PORT:", os.environ.get("KRUS_WEB_PORT"))
    print("KRUS_API_HOST:", os.environ.get("KRUS_API_HOST"))
    print("KRUS_API_PORT:", os.environ.get("KRUS_API_PORT"))
    print("KRUS_DB_HOST:",  os.environ.get("KRUS_DB_HOST"))
    print("KRUS_DB_PORT:",  os.environ.get("KRUS_DB_PORT"))
    print("KRUS_DB_NAME:",  os.environ.get("KRUS_DB_NAME"))
    print("KRUS_DB_USER:",  os.environ.get("KRUS_DB_USER"))
    print("KRUS_DB_PASSWORD:",  os.environ.get("KRUS_DB_PASSWORD"))

def serve(path):
    host = os.environ.get("KRUS_API_HOST")
    port = os.environ.get("KRUS_API_PORT")

    if not host or not port:
        print("ERROR you have to source .env KRUS_WEB_HOST and KRUS_WEB_PORT")
        sys.exit()


    os.chdir(path)
    call(["php", "-S", host+":"+port])


def docker():
    call(["docker-compose", "up"])


def dockerbuild():
    call(["mkdir", "db"])
    call(["docker-compose", "up", "--build"])



if __name__ == "__main__":

    change_working_directory_to_script_directory()

    load_environment_file()

    parser = argparse.ArgumentParser()
    parser.add_argument("-e","--env", help="Set environment variables", action="store_true")
    parser.add_argument("-z","--zip", nargs=1, help="<version>")
    parser.add_argument("-l", "--list", help="List environment variables", action="store_true")
    parser.add_argument("-f", "--fetch", help="Fetch depencies from bower, npm and composer",action="store_true")
    parser.add_argument("-b", "--build", nargs=1, help="<version>")
    parser.add_argument("-bo", "--build-only", help="Build only to /dist",action="store_true")
    parser.add_argument("-m", "--migseed", help="migrate:refresh + seed:refresh", action="store_true")
    parser.add_argument("-t", "--test", nargs=1, help="Run specific test")
    parser.add_argument("-ta", "--test-all", help="Run all tests", action="store_true")
    parser.add_argument("-s", "--serve", nargs=1, help="<serverpath>")
    parser.add_argument("-d", "--docker", help="Run docker-compose up", action="store_true")
    parser.add_argument("-db", "--dockerbuild", help="Run docker-compose up + build", action="store_true")

    argv = parser.parse_args()

    if argv.fetch:
        fetch()

    elif argv.build:
        build(argv.build[0])

    elif argv.build_only:
        build_only()

    elif argv.migseed:
        migseed()

    elif argv.test:
        test(argv.test[0])

    elif argv.test_all:
        test_all()

    elif argv.env:
        setdevenv()

    elif argv.serve:
        serve(argv.serve[0])

    elif argv.docker:
        docker()

    elif argv.dockerbuild:
        dockerbuild()

    elif argv.list:
        listenv()

    elif argv.zip:
        zip_distribution(argv.zip[0])
