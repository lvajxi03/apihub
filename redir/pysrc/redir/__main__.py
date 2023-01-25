#!/usr/bin/env python3

from flask import Flask, request, jsonify, make_response
import json
import importlib
from redir import features, config, database, auth, codes, utils
from waitress import serve
import sys

app = Flask(__name__)
try:
    conf = config.get(sys.argv[1])
except IndexError:
    print("Usage: python -m redir <config-file-name>")
    sys.exit(codes.ERROR_NO_DATA)

dbconf = conf['database']

db = database.Db()
db.connect(dbconf['hostname'],
           dbconf['username'],
           dbconf['password'],
           dbconf['database'])

@app.route("/features")
def features_info():
    return features.info()


@app.route("/<first>/<second>/<third>/<fourth>/<fifth>", methods=['GET', 'POST', 'PUT', 'DELETE'])
@app.route("/<first>/<second>/<third>/<fourth>", methods=['GET', 'POST', 'PUT', 'DELETE'])
@app.route("/<first>/<second>/<third>", methods=['GET', 'POST', 'PUT', 'DELETE'])
@app.route("/<first>/<second>", methods=['GET', 'POST', 'PUT', 'DELETE'])
@app.route("/<first>", methods=['GET', 'POST', 'PUT', 'DELETE'])
def module(first, second = '', third = '', fourth = '', fifth = ''):
    actions = [second, third, fourth, fifth]
    if "Authorization" in request.headers:
        header = request.headers["Authorization"]
        if header.startswith("Bearer "):
            header = header.replace("Bearer ", "")
            try:
                mod = importlib.import_module(f"redir.modules.{first}")
            except ImportError as ie:
                return {"err": str(ie)}
                mod = importlib.import_module("redir.modules.module")
            return mod.process(request.method, db, header, actions=actions, data=request.json)
    return utils.make_unauthorized()

@app.route("/")
def root():
    return utils.make_unauthorized()

serve(app, host='0.0.0.0', port=5000)
