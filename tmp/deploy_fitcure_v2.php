<?php
// Write test PHP files and immediately try to access them

$unique = time();

// Test 1: Write to /home/busnisscard/public_html/fitcure.online/
$path1 = '/home/busnisscard/public_html/fitcure.online';
@mkdir($path1, 0755, true);
$testFile1 = "$path1/fitcure_test_$unique.php";
file_put_contents($testFile1, '<?php echo "OK1:' . $unique . '";');
$testHtml1 = "$path1/fitcure_test_$unique.html";
file_put_contents($testHtml1, "OK1-html:$unique");

// Test 2: Write to /home/busnisscard/fitcure.online/
$path2 = '/home/busnisscard/fitcure.online';
@mkdir($path2, 0755, true);
$testFile2 = "$path2/fitcure_test_$unique.php";
file_put_contents($testFile2, '<?php echo "OK2:' . $unique . '";');
$testHtml2 = "$path2/fitcure_test_$unique.html";
file_put_contents($testHtml2, "OK2-html:$unique");

// Test 3: Write to /home/busnisscard/public_html/ (main domain root)
$path3 = '/home/busnisscard/public_html';
$testFile3 = "$path3/fitcure_test_$unique.php";
file_put_contents($testFile3, '<?php echo "OK3:' . $unique . '";');
$testHtml3 = "$path3/fitcure_test_$unique.html";
file_put_contents($testHtml3, "OK3-html:$unique");

echo "Test files created with ID: $unique\n";
echo "Run these URLs in your browser:\n\n";
echo "  https://fitcure.online/fitcure_test_$unique.php\n";
echo "  https://fitcure.online/fitcure_test_$unique.html\n";
echo "  https://busnisscard.com/fitcure_test_$unique.php\n";
echo "  https://busnisscard.com/fitcure_test_$unique.html\n";
