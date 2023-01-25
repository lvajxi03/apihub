#!/usr/bin/env python

from flask import make_response
from redir import codes, auth, utils

def process(method: str, db, token, actions=None, data=None):
    if auth.auth(db, token):
        return make_response(
            {"error-code": codes.ERROR_NOT_FOUND,
             "error-message": "Not found.",
             "data": {"message": "Howdy, stranger!"}},
            codes.RESPONSE_NOT_FOUND,
            {"Content-type": "application/json"})
    return utils.make_unauthorized()
                         
