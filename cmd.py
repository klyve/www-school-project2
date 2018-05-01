#!/usr/local/bin/python3
# -*- coding:utf-8 -*-

import argparse
from subprocess import call
import os
import sys


def changeWorkingDirectoryToScriptDirectory():
    return

def setenv(webhost,webport,dbhost,dbport,dbpass=""):

    with open(".env", "w") as envfile:
        envfile.write("DIR=\"$( cd \"$( dirname \"${BASH_SOURCE[0]}\" )\" && pwd )\"\n")
        envfile.write("alias krustool=\"$DIR/cmd.py\"\n")
        envfile.write("echo \"aliased $DIR/cmd.py -> krustool\"\n\n")

        envfile.write("export KRUS_ROOT=$DIR\n")
        envfile.write("export KRUS_WEB_HOST="+webhost+"\n")
        envfile.write("export KRUS_WEB_PORT="+webport+"\n")
        envfile.write("export KRUS_DB_HOST="+dbhost+"\n")
        envfile.write("export KRUS_DB_PORT="+dbport+"\n")
        envfile.write("export KRUS_DB_PASS="+dbpass+"\n")

        envfile.write("echo KRUS_ROOT:     $KRUS_ROOT\n")
        envfile.write("echo KRUS_WEB_HOST: $KRUS_WEB_HOST\n")
        envfile.write("echo KRUS_WEB_PORT: $KRUS_WEB_PORT\n")
        envfile.write("echo KRUS_DB_HOST:  $KRUS_DB_HOST\n")
        envfile.write("echo KRUS_DB_PORT:  $KRUS_DB_PORT\n")
        envfile.write("echo KRUS_DB_PASS:  $KRUS_DB_PASS\n")


    with open("apitests/.env", "w") as envfile:
        envfile.write("KRUS_WEB_HOST="+webhost+"\n")
        envfile.write("KRUS_WEB_PORT="+webport+"\n")


def install():
    os.chdir("apitests")
    call(["npm","install"]);
    os.chdir("..")
    os.chdir("server")
    call(["composer","install"]);
    os.chdir("..")

def migseed():
    call(["php", "server/toolbox", "migrate:refresh"]);
    call(["php", "server/toolbox", "seed:refresh"]);

def test():
    call(["php", "server/toolbox", "migrate:refresh"]);
    call(["php", "server/toolbox", "seed:refresh"]);

    os.chdir("apitests")
    call(["ava", "--fail-fast", "--verbose", "tests/"])
    os.chdir("..")

def listenv():
    print("KRUS_ROOT:",     os.environ.get("KRUS_ROOT"))
    print("KRUS_WEB_HOST:", os.environ.get("KRUS_WEB_HOST"))
    print("KRUS_WEB_PORT:", os.environ.get("KRUS_WEB_PORT"))
    print("KRUS_DB_HOST:",  os.environ.get("KRUS_DB_HOST"))
    print("KRUS_DB_PORT:",  os.environ.get("KRUS_DB_PORT"))
    print("KRUS_DB_PASS:",  os.environ.get("KRUS_DB_PASS"))

def serve():
    host = os.environ.get("KRUS_WEB_HOST")
    port = os.environ.get("KRUS_WEB_PORT")

    if not host or not port:
        print("ERROR you have to source .env KRUS_WEB_HOST and KRUS_WEB_PORT")
        sys.exit()


    os.chdir("server")
    call(["php", "-S", host+":"+port])


def docker():
    call(["docker-compose", "up"])


def dockerbuild():
    call(["mkdir", "db"])
    call(["docker-compose", "up", "--build"])

if __name__ == "__main__":

    scriptpath = os.path.dirname(os.path.realpath(__file__))
    os.chdir(scriptpath)

    parser = argparse.ArgumentParser()
    parser.add_argument("-e", "--env", nargs=2, help="web:port db:port dbpass. example 127.0.0.1:4000 :3306. Outputing .env")
    
    parser.add_argument("-l", "--list", help="List environment variables",action="store_true")
    parser.add_argument("-i", "--install", help="install all npm and composer deps",action="store_true")
    parser.add_argument("-m", "--migseed", help="migrate:refresh + seed:refresh", action="store_true")
    parser.add_argument("-t", "--test", help="Run all tests", action="store_true")
    parser.add_argument("-s", "--serve", help="Run webserver",action="store_true")
    parser.add_argument("-d", "--docker", help="Run docker-compose up", action="store_true")
    parser.add_argument("-db", "--dockerbuild", help="Run docker-compose up + build", action="store_true")

    argv = parser.parse_args()

    if argv.install:
        install()

    elif argv.migseed:
        migseed()

    elif argv.test:
        test()

    elif argv.env:

        if ":" not in argv.env[0] or ":" not in argv.env[1]:
            print("ERROR has to have a ':' separator like web:port db:port")
            sys.exit()

        webargs = argv.env[0].split(":")
        dbargs  = argv.env[1].split(":")
        #dbpass  = argv.env[2]
        setenv(webargs[0], webargs[1],dbargs[0], dbargs[1])

    elif argv.serve:
        serve()

    elif argv.docker:
        docker()

    elif argv.dockerbuild:
        dockerbuild()

    elif argv.list:
        listenv()
