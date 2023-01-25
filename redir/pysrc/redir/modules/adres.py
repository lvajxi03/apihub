#!/usr/bin/env python

from flask import make_response
from redir import codes, auth, utils
import requests, time
import mysql.connector

def process(method:str, db, token: str, actions=None, data=None):    
    if auth.auth(db, token):
        if method == "PUT":
            if data:
                if 'hostname' in data and 'ip' in data:
                    hn = data["hostname"]
                    ip = data["ip"]
                    now = time.localtime()
                    tdata = time.strftime("%Y-%m-%d", now)
                    tgodzina = time.strftime("%H:%M:%S", now)
                    query = f"INSERT INTO adres y (data, godzina, hostname, ip) VALUES ('{tdata}', '{tgodzina}', '{hn}', '{ip}')"
                    try:
                        db.query(query)
                        if db.cursor.rowcount > 0:
                            if "redir" in data:
                                target_host = data["redir"]["target-host"]
                                target_token = data["redir"]["target-token"]
                                headers = {"Content-type": "application/json",
                                           "Authorization": f"Bearer {target_token}"}
                                d = {"hostname": hn,
                                     "ip": ip}
                                response = requests.put(f"{target_host}/adres", headers=headers, json=d)
                                return make_response(response.text, response.status_code, {"Content-type": "application/json"})
                            else:
                                return utils.make_created()
                        else:
                            return utils.make_db_error()
                    except mysql.connector.errors.Error:
                        return utils.make_db_error()
                else:
                    return utils.make_invalid_data()
            else:
                return utils.make_no_data()
        else:
            return utils.make_not_allowed()
    return utils.make_unauthorized()
