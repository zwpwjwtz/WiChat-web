#!/bin/bash
# Extract gettext strings in source files

PACKAGE_NAME="Wichat-web"

SRC_FILES="\
    register/auth_code.php \
    register/register_normal.php \
    include/templates/auth_code.html \
    include/templates/register.html \
    include/templates/register_ok.html"

SRC_CHARSET="UTF-8"
    
TARGET_DIR="translations"

TARGET_FILE=$TARGET_DIR/$PACKAGE_NAME.pot

xgettext $SRC_FILES --from-code $SRC_CHARSET -d $PACKAGE_NAME -o $TARGET_FILE --package-name $PACKAGE_NAME