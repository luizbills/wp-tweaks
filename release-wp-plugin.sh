#!/usr/bin/env bash

# By Luiz Bills based on work by Barry Kooij and Mike Jolley
# License: GPL v3

# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>

# ----- START EDITING HERE -----

# The slug of your WordPress.org plugin
WP_PLUGIN_SLUG="wp-tweaks"

# GITHUB user who owns the repo
GITHUB_REPO_OWNER="luizbills"

# GITHUB Repository name
GITHUB_REPO_NAME="wp-tweaks"

# folder with plugin banner and screenshots
# note: you should commit these assets separately
PLUGIN_ASSETS_DIR="_wp-assets"

# ----- STOP EDITING HERE -----

set -e
clear

# ASK INFO
echo "--------------------------------------------"
echo "      Github to WordPress.org RELEASER      "
echo "--------------------------------------------"
read -p "RELEASE VERSION: " VERSION
echo "--------------------------------------------"
echo ""
echo "Before continuing, confirm that you have done the following :)"
echo ""
read -p " - Added a changelog for "${VERSION}"?"
read -p " - Set version in the readme.txt and main file to "${VERSION}"?"
read -p " - Set stable tag in the readme.txt file to "${VERSION}"?"
read -p " - Updated the POT file?"
read -p " - Committed all changes up to GITHUB?"
echo ""
read -p "PRESS [ENTER] TO BEGIN RELEASING "${VERSION}
clear

# VARS
ROOT_PATH=$(pwd)"/"
TEMP_GITHUB_REPO=${WP_PLUGIN_SLUG}"-git"
TEMP_SVN_REPO=${WP_PLUGIN_SLUG}"-svn"
SVN_REPO="http://plugins.svn.wordpress.org/"${WP_PLUGIN_SLUG}"/"
GIT_REPO="https://github.com/"${GITHUB_REPO_OWNER}"/"${GITHUB_REPO_NAME}".git"

# DELETE OLD TEMP DIRS
rm -Rf $ROOT_PATH$TEMP_GITHUB_REPO
rm -Rf $ROOT_PATH$TEMP_SVN_REPO

# CLONE GIT DIR
echo "Cloning GIT repository from GITHUB"
git clone --progress --recurse-submodules $GIT_REPO $TEMP_GITHUB_REPO || { echo "Unable to clone repo."; exit 1; }

# MOVE INTO GIT DIR
cd "$ROOT_PATH$TEMP_GITHUB_REPO"

# LIST BRANCHES
clear
git fetch origin
echo "WHICH BRANCH DO YOU WISH TO DEPLOY?"
git branch -r || { echo "Unable to list branches."; exit 1; }
echo ""
read -p "origin/" BRANCH

# Switch Branch
echo "Switching to branch"
git checkout ${BRANCH} || { echo "Unable to checkout branch."; exit 1; }

if [[ -f "composer.json" ]];
then
	echo "Installing composer packages"
	composer install --no-dev || { echo "Unable to install composer packages."; exit 1; }
fi

echo ""
read -p "PRESS [ENTER] TO DEPLOY BRANCH "${BRANCH}

# REMOVE UNWANTED FILES & FOLDERS
echo "Removing unwanted files"
rm -Rfv ./.* 2> /dev/null || true # remove all dot files
rm -Rf ./tests
rm -f ./phpunit.xml
rm -f ./phpunit.xml.dist
rm -f ./README.md
rm -f ./CONTRIBUTING.md
rm -f ./"${0}" # don't deploy this file
echo "Removed unwanted files"

# don't deploy WordPress plugin banners and icons
if [[ -n PLUGIN_ASSETS_DIR ]];
then
    rm -Rf ${PLUGIN_ASSETS_DIR}
fi

cd $ROOT_PATH

# CHECKOUT SVN DIR IF NOT EXISTS
if [[ ! -d $TEMP_SVN_REPO ]];
then
	echo "Checking out WordPress.org plugin repository"
	svn checkout $SVN_REPO $TEMP_SVN_REPO || { echo "Unable to checkout repo."; exit 1; }
fi

# MOVE INTO SVN DIR
cd "$ROOT_PATH$TEMP_SVN_REPO"

# UPDATE SVN
echo "Updating SVN"
svn update || { echo "Unable to update SVN."; exit 1; }

# DELETE TRUNK
echo "Replacing trunk"
rm -Rf trunk/

# DELETE VERSION TAG
rm -Rf tags/${VERSION}

# COPY GIT DIR TO TRUNK
cp -R "$ROOT_PATH$TEMP_GITHUB_REPO" trunk/

# DO THE ADD ALL NOT KNOWN FILES UNIX COMMAND
svn add --force * --auto-props --parents --depth infinity -q

# DO THE REMOVE ALL DELETED FILES UNIX COMMAND
MISSING_PATHS=$( svn status | sed -e '/^!/!d' -e 's/^!//' )

# iterate over filepaths
for MISSING_PATH in $MISSING_PATHS;
do
    svn rm --force "$MISSING_PATH"
done

# COPY TRUNK TO TAGS/$VERSION
echo "Copying trunk to new tag"
svn copy trunk tags/${VERSION} || { echo "Unable to create tag."; exit 1; }

# DO SVN COMMIT
clear
echo "Showing SVN status"
svn status

# PROMPT USER
echo ""
read -p "PRESS [ENTER] TO COMMIT RELEASE "${VERSION}" TO WORDPRESS.ORG"
echo ""

# DEPLOY
echo ""
echo "Committing to WordPress.org...this may take a while..."
svn commit -m "Release "${VERSION}", see readme.txt for the changelog." || { echo "Unable to commit."; exit 1; }

# REMOVE THE TEMP DIRS
echo "CLEANING UP"
rm -Rf "$ROOT_PATH$TEMP_GITHUB_REPO"
rm -Rf "$ROOT_PATH$TEMP_SVN_REPO"

# DONE, BYE
echo "RELEASER DONE :D"
