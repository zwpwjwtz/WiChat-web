#!/bin/bash
# Extract gettext strings in source files

PACKAGE_NAME="Wichat-web"

TEMPLATE="translations/$PACKAGE_NAME.pot"
    
TARGET_DIR="translations"

TARGET_FILE=$(ls $TARGET_DIR/*.po)

for poFile in $TARGET_FILE; do
    msgmerge $poFile $TEMPLATE -o $poFile
done
