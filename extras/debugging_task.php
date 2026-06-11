<?php
require_once 'required.php';

try {

    $conn = mysqli_connect(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME
    );

    $id = filter_input(
        INPUT_GET,
        'id',
        FILTER_VALIDATE_INT
    );

    if($id === null){
        throw new Exception(
            'User ID is required'
        );
    }

    if ($id === false) {
        throw new Exception(
            'User ID must be a valid integer'
        );
    }

    $stmt = mysqli_prepare(
        $conn,
        "SELECT name FROM users WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        echo "User not found";
    } else {

        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['name'];
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} catch (Throwable $e) {
    echo $e->getMessage();
}
