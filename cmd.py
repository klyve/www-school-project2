#!/usr/local/bin/python3
# -*- coding:utf-8 -*-

import argparse
from subprocess import call
import os
import sys


def change_working_directory_to_script_directory():
    scriptpath = os.path.dirname(os.path.realpath(__file__))
    os.chdir(scriptpath)


def setdevenv(webhost="127.0.0.1",
              webport="3000",
              apihost="127.0.0.1",
              apiport="4000",
              dbhost="127.0.0.1",
              dbport="3306"):

    with open(".env", "w") as envfile:
        envfile.write("DIR=\"$( cd \"$( dirname \"${BASH_SOURCE[0]}\" )\" && pwd )\"\n")
        envfile.write("alias krustool=\"$DIR/cmd.py\"\n")
        envfile.write("echo \"aliased $DIR/cmd.py -> krustool\"\n\n")

        envfile.write("export KRUS_ROOT=$DIR\n")
        envfile.write("export KRUS_WEB_HOST="+webhost+"\n")
        envfile.write("export KRUS_WEB_PORT="+webport+"\n")
        envfile.write("export KRUS_API_HOST="+apihost+"\n")
        envfile.write("export KRUS_API_PORT="+apiport+"\n")
        envfile.write("export KRUS_DB_HOST="+dbhost+"\n")
        envfile.write("export KRUS_DB_PORT="+dbport+"\n")

        envfile.write("echo KRUS_ROOT:     $KRUS_ROOT\n")
        envfile.write("echo KRUS_WEB_HOST: $KRUS_WEB_HOST\n")
        envfile.write("echo KRUS_WEB_PORT: $KRUS_WEB_PORT\n")
        envfile.write("echo KRUS_API_HOST: $KRUS_API_HOST\n")
        envfile.write("echo KRUS_API_PORT: $KRUS_API_PORT\n")
        envfile.write("echo KRUS_DB_HOST:  $KRUS_DB_HOST\n")
        envfile.write("echo KRUS_DB_PORT:  $KRUS_DB_PORT\n")


    with open("apitests/.env", "w") as envfile:
        envfile.write("KRUS_API_HOST="+apihost+"\n")
        envfile.write("KRUS_API_PORT="+apiport+"\n")


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
    call(["ava", "--fail-fast", "--verbose", "--watch", "tests/"])
    os.chdir("..")

def listenv():
    print("KRUS_ROOT:",     os.environ.get("KRUS_ROOT"))
    print("KRUS_WEB_HOST:", os.environ.get("KRUS_WEB_HOST"))
    print("KRUS_WEB_PORT:", os.environ.get("KRUS_WEB_PORT"))
    print("KRUS_API_HOST:", os.environ.get("KRUS_API_HOST"))
    print("KRUS_API_PORT:", os.environ.get("KRUS_API_PORT"))
    print("KRUS_DB_HOST:",  os.environ.get("KRUS_DB_HOST"))
    print("KRUS_DB_PORT:",  os.environ.get("KRUS_DB_PORT"))

def serve():
    host = os.environ.get("KRUS_API_HOST")
    port = os.environ.get("KRUS_API_PORT")

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

    change_working_directory_to_script_directory()

    parser = argparse.ArgumentParser()
    parser.add_argument("-e","--env", nargs=3, help="Sets environemnt variables for the devenv")
    
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
        setdevenv(webport=argv.env[0], apiport=argv.env[1], dbport=argv.env[2])

    elif argv.serve:
        serve()

    elif argv.docker:
        docker()

    elif argv.dockerbuild:
        dockerbuild()

    elif argv.list:
        listenv()
