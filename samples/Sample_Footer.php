<?php
/**
 * Footer file
 */
// Do not show execution time for index
if (!IS_INDEX) {
    echo date('H:i:s'), " Done writing file(s)", EOL;
    echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;
}
// Show message when executed with CLI, show links when using browsers
if (CLI) {
    echo 'The results are stored in the "results" subdirectory.', EOL;
} else {
    if (!IS_INDEX) {
        $types = array('docx', 'odt', 'rtf');
        echo '<p>&nbsp;</p>';
        echo '<p>Results: ';
        foreach ($types as $type) {
            $result = 'results/' . SCRIPT_FILENAME . '.' . $type;
            if (file_exists($result)) {
                echo "<a href='{$result}' class='btn btn-primary'>{$type}</a> ";
            }
        }
        echo '</p>';
    }
?>
</div>
<script src="bootstrap/js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
<?php
} // if (CLI)

