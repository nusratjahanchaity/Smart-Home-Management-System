<!DOCTYPE html>
<html>

<head>
    <title>Smart Home Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f9f9f9;
        }

        h1 {
            margin-top: 20px;
            font-size: 32px;
        }

        .mode-buttons {
            margin: 10px 0;
        }

        .mode-buttons button {
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .normal-mode {
            background-color: #d3d3d3;
            color: black;
        }

        .sql-mode {
            background-color: #d3d3d3;
            color: black;
        }

        .selected {
            border: 2px solid #000;
            background-color: #32cd32;
            color: white;
        }

        .work-in-progress {
            margin-top: 20px;
            font-size: 20px;
            color: #00000;
        }

        .container {
            display: flex;
            justify-content: space-between;
            width: 80%;
            height: 500px;
            margin-top: 20px;
        }

        .input-section {
            display: flex;
            flex-direction: column;
            width: 48%;
        }

        .query-section textarea {
            width: 100%;
            height: 220px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            background-color: #e0e0e0;
        }

        .query-section button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .output-section {
            background-color: #fffacd;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            /* height: 600px; */
            width: 48%;
            overflow-y: scroll;
            max-height: relative;
        }

        .output-section h2 {
            margin: 0;
        }

        .tables-section {
            margin-top: 20px;
            width: 100%;
            background-color: #b3e5fc;
            padding: 10px;
            border-radius: 5px;
        }

        .tables-section h2 {
            margin: 0;
        }

        .tables-section ul {
            /* margin-bottom: 15px; */
            padding: 0;
            list-style: none;
        }

        .tables-section ul li {
            margin: 5px 0;
            margin-left: 10px;
            cursor: pointer;
            color: black;
            text-decoration: underline;
            font-weight: bold;
        }

        table {
            margin-top: 10px;
            border-collapse: collapse;
            width: 100%;
            font-family: monospace;
            font-size: 16px;
            text-align: left;
        }

        th {
            background-color: gray;
            color: white;
            border-right: 1px solid #ddd;
            padding: 8px;
        }

        tr {
            color: black;
            border-bottom: 1px solid #ddd;
        }

        
        td {
            border-right: 1px solid #ddd;
            padding: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tr:nth-child(odd) {
            background-color: #e2e2e2;
        }

        tr:hover {
            background-color:rgb(165, 165, 165);
        }
    </style>
    <script>
        function selectMode(mode) {
            const normalModeButton = document.getElementById('normal-mode');
            const sqlModeButton = document.getElementById('sql-mode');
            const workInProgressMessage = document.getElementById('work-in-progress');
            const sqlMode = document.getElementById('sql-mode-container');

            if (mode === 'normal') {
                normalModeButton.classList.add('selected');
                sqlModeButton.classList.remove('selected');
                workInProgressMessage.style.display = 'block';
                sqlMode.style.display = 'none';
            } else if (mode === 'sql') {
                sqlModeButton.classList.add('selected');
                normalModeButton.classList.remove('selected');
                workInProgressMessage.style.display = 'none';
                sqlMode.style.display = 'flex';
            }
        }

        function executeQuery(tableName) {
            const queryForm = document.getElementById('query-form');
            const queryTextarea = document.getElementById('query');
            queryTextarea.value = `SELECT * FROM ${tableName}`;
            queryForm.submit();
        }
    </script>
</head>

<body onload="selectMode('sql')">
    <h1>Smart Home Management System</h1>
    <div class="mode-buttons">
        <button id="normal-mode" class="normal-mode" onclick="selectMode('normal')">Normal Mode</button>
        <button id="sql-mode" class="sql-mode" onclick="selectMode('sql')">SQL Mode</button>
    </div>

    <div id="work-in-progress" class="work-in-progress" style="display: none;">
        Work in Progress...
    </div>

    <div class="container" id="sql-mode-container" style="display: none;">
        <div class="input-section">
            <div class="query-section">
                <form method="POST" id="query-form">
                    <textarea id="query" name="query" placeholder="Write your SQL queries"></textarea>
                    <button type="submit">RUN</button>
                </form>
            </div>
            <div class="tables-section">
                <h2>Tables</h2>
                <ul>
                    <li onclick="executeQuery('Users')">Users</li>
                    <li onclick="executeQuery('Rooms')">Rooms</li>
                    <li onclick="executeQuery('Room_Access')">Room_Access</li>
                    <li onclick="executeQuery('Devices')">Devices</li>
                    <li onclick="executeQuery('Schedules')">Schedules</li>
                </ul>
            </div>
        </div>

        <div class="output-section">
            <h2>Output</h2>
            <table>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
                    $userQuery = $_POST['query'];

                    $conn = oci_connect('SYSTEM', 'C233470', '//localhost/XE');
                    if (!$conn) {
                        echo 'Failed to connect to Oracle' . "<br>";
                    } else {
                        $stid = oci_parse($conn, $userQuery);
                        if (!$stid) {
                            $m = oci_error($conn);
                            trigger_error('Could not parse statement: ' . $m['message'], E_USER_ERROR);
                        }

                        $r = oci_execute($stid);
                        if (!$r) {
                            $m = oci_error($stid);
                            trigger_error('Could not execute statement: ' . $m['message'], E_USER_ERROR);
                        }

                        echo '<tr>';
                        $ncols = oci_num_fields($stid);
                        for ($i = 1; $i <= $ncols; $i++) {
                            $colname = oci_field_name($stid, $i);
                            echo '<th>' . htmlentities($colname, ENT_QUOTES) . '</th>';
                        }
                        echo '</tr>';

                        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
                            print '<tr>';
                            foreach ($row as $item) {
                                print '<td>' . ($item !== null ? htmlentities($item, ENT_QUOTES) : '&nbsp') . '</td>';
                            }
                            print '</tr>';
                        }

                        oci_close($conn);
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>

</html>