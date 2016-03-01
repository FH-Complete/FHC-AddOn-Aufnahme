#!/bin/sh
#
# This archive is part of the FHC-AddOn Aufnahme source code.
# 
# The source code of this software is under the terms of one of 
# GPL v3 licence
# 
# ABOUT:
# =====
# This script will install some dependencies and configure default files
# 
# WARNING:
# Of course that Apache, PostgreSQL and Postgis should already be running 
# because it is not responsability of Whisperocity to install other software
# Anyway, the following steps are an example to help install a full system:
# 
# ------------------------------------------------------------------------------
# =============================================================================
# Install script for FHC-AddOn-Aufnahme on PHPCI
# =============================================================================

CI_VERSION="3.0"

echo "==============================================================="
echo "Installing FHC-AddOn-Aufnahme (installPHPCI.sh)"
echo "==============================================================="

cwd=$(pwd)
echo "Starting..."
cp cis/index.dist.php cis/index.php
cp cis/application/config/config.dist.php cis/application/config/config.php
cp cis/application/config/database.dist.php cis/application/config/database.php
cp cis/application/config/aufnahme.dist.php cis/application/config/aufnahme.php
# ./composer.phar install
# ln -s "$cwd/vendor" ./web/js/vendor
chgrp -R www-data *

echo "Done!"
exit 0
