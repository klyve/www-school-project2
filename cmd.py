#!/usr/local/bin/python3
# -*- coding:utf-8 -*-

import argparse
from subprocess import call
import os
import sys

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

def setenv(webhost,webport,dbhost,dbport):

    with open(".env", "w") as envfile:
        envfile.write("export KRUS_WEB_HOST="+webhost+"\n")
        envfile.write("export KRUS_WEB_PORT="+webport+"\n")
        envfile.write("export KRUS_DB_HOST="+dbhost+"\n")
        envfile.write("export KRUS_DB_PORT="+dbport+"\n")

        envfile.write("echo KRUS_WEB_HOST: $KRUS_WEB_HOST\n")
        envfile.write("echo KRUS_WEB_PORT: $KRUS_WEB_PORT\n")
        envfile.write("echo KRUS_DB_HOST:  $KRUS_DB_HOST\n")
        envfile.write("echo KRUS_DB_PORT:  $KRUS_DB_PORT\n")


    with open("apitests/.env", "w") as envfile:
        envfile.write("KRUS_WEB_HOST="+webhost+"\n")
        envfile.write("KRUS_WEB_PORT="+webport+"\n")



def serve():
    host = os.environ.get("KRUS_WEB_HOST")
    port = os.environ.get("KRUS_WEB_PORT")

    if not host or not port:
        print("ERROR you have to source .env KRUS_WEB_HOST and KRUS_WEB_PORT")
        sys.exit()


    os.chdir("server")
    call(["php", "-S", host+":"+port])


if __name__ == "__main__":

    parser = argparse.ArgumentParser()
    parser.add_argument( "-i", "--install", help="install all npm and composer deps",action="store_true")
    parser.add_argument("-m", "--migseed", help="migrate:refresh + seed:refresh", action="store_true")
    parser.add_argument("-t", "--test", help="Run all tests", action="store_true")
    parser.add_argument("-e", "--env", nargs=2, help="web:port db:port. example 127.0.0.1:4000 :3306. Outputing .env")
    parser.add_argument("-s", "--serve", help="Run webserver",action="store_true")
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
        setenv(webargs[0], webargs[1], dbargs[0], dbargs[1])

    elif argv.serve:
        serve()
