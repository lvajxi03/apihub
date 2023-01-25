#!/usr/bin/env python

from flask import make_response
from redir import codes


def make_created():
    return make_response(
        {"error-code": codes.ERROR_OK,
         "error-message": "Created."},
        codes.RESPONSE_CREATED,
        {"Content-type": "application/json"})


def make_unauthorized():
    return make_response(
        {"error-code": codes.ERROR_NOT_AUTHORIZED,
         "error-message": "Unauthorized."},
        codes.RESPONSE_UNAUTHORIZED,
        {"Content-type": "application/json"})


def make_not_found():
    return make_response(
        {"error-code": codes.ERROR_NOT_FOUND,
         "error-message": "Not found."},
        codes.RESPONSE_NOT_FOUND,
        {"Content-type": "application/json"})


def make_not_allowed():
    return make_response(
        {"error-code": codes.ERROR_NOT_ALLOWED,
         "error-message": "Method not allowed."},
        codes.RESPONSE_METHOD_NOT_ALLOWED,
        {"Content-type": "application/json"})


def make_no_data():
    return make_response(
        {"error-code": codes.ERROR_NO_DATA,
         "error-message": "No data."},
        codes.RESPONSE_BAD_REQUEST,
        {"Content-type": "application/json"})


def make_invalid_data():
    return make_response(
        {"error-code": codes.ERROR_INVALID_DATA,
         "error-message": "No data."},
        codes.RESPONSE_NOT_ACCEPTABLE,
        {"Content-type": "application/json"})


def make_db_error():
    return make_response(
        {"error-code": codes.ERROR_DB_ERROR,
         "error-message": "Database error."},
        codes.RESPONSE_CONFLICT,
        {"Content-type": "application/json"})
