#!/bin/bash

echo "--DEBUG--"
echo "TRAVIS_REPO_SLUG: $TRAVIS_REPO_SLUG"
echo "TRAVIS_PHP_VERSION: $TRAVIS_PHP_VERSION"
echo "TRAVIS_PULL_REQUEST: $TRAVIS_PULL_REQUEST"

if [ "$TRAVIS_REPO_SLUG" == "PHPOffice/PHPWord" ] && [ "$TRAVIS_PULL_REQUEST" == "false" ] && [ "$TRAVIS_PHP_VERSION" == "5.5" ]; then

  echo -e "Publishing PHPDoc...\n"

  cp -R build/docs $HOME/docs-latest
  cp -R build/coverage $HOME/coverage-latest

  cd $HOME
  git config --global user.email "travis@travis-ci.org"
  git config --global user.name "travis-ci"
  git clone --quiet --branch=gh-pages https://${GH_TOKEN}@github.com/PHPOffice/PHPWord gh-pages > /dev/null

  cd gh-pages
  echo "--DEBUG : Suppression"
  git rm -rf ./docs/$TRAVIS_BRANCH

  echo "--DEBUG : Dossier"
  mkdir -p docs/$TRAVIS_BRANCH
  mkdir -p coverage/$TRAVIS_BRANCH

  echo "--DEBUG : Copie"
  cp -Rf $HOME/docs-latest/* ./docs/$TRAVIS_BRANCH/
  cp -Rf $HOME/coverage-latest/* ./coverage/$TRAVIS_BRANCH/

  echo "--DEBUG : Git"
  git add -f .
  git commit -m "PHPDocumentor (Travis Build: $TRAVIS_BUILD_NUMBER  - Branch: $TRAVIS_BRANCH)"
  git push -fq origin gh-pages > /dev/null

  echo -e "Published PHPDoc to gh-pages.\n"

fi
