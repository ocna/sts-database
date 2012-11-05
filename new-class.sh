#!/bin/sh
USAGE="Usage: new-class.sh [-u] [-i]  <class>"

src='application/src/'
unit='application/tests/unit/'
int='application/tests/integration/'
[[ "${@: -1}" =~ (.*)\/(.*$) ]]
class=${BASH_REMATCH[2]}
path=${BASH_REMATCH[1]}

namespace=`echo $path | sed 's/\//\\\/g'`

classText="<?php\nnamespace $namespace;\nclass ${class}\n{\n}";
testText="<?php\nnamespace $namespace;\n\nuse ${namespace}\\${class};\n\nclass ${class}Test extends \PHPUnit_Framework_TestCase\n{\n}";

mkdir -p "$src$path";
classFile="$src$path/$class.php"
echo "Creating: $classFile";
echo $classText >> $classFile;

while getopts ":ui" OPTIONS; do
  case $OPTIONS in
    u ) 
	mkdir -p "$unit$path";
	unitTestFile="$unit$path/${class}Test.php"
	echo "Creating: $unitTestFile";
	echo $testText >> $unitTestFile;
	;;
    i ) 
	mkdir -p "$int$path";
	intTestFile="$int$path/${class}Test.php"
	echo "Creating: $intTestFile";
	echo $testText >>  $intTestFile;
	;;
    h ) echo $USAGE;;
    \? ) echo $USAGE
         exit 1;;
    * ) echo $usage
        exit 1;;
  esac
done