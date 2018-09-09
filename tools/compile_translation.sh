#!/bin/bash
# Compile translation files, i.e. converting PO files to MO files

PACKAGE_NAME="Wichat-web"
SRC_DIR="translations"
SRC_FILES=$(ls $SRC_DIR/*.po)
TARGET_DIR="include/locale"

for src in $SRC_FILES; do
    poFile=${src##*/}
    language=${poFile%.po}
    LANG_DIR=$TARGET_DIR/$language/LC_MESSAGES
    mkdir -p $LANG_DIR
    msgfmt $src -o $LANG_DIR/$PACKAGE_NAME.mo
done
