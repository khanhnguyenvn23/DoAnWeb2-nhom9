<?php 
require_once  "../../includes/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if($action == 'save_role') {
        $name_permission = $_POST['name_permission'];

        $sql = "INSERT INTO permission (name) VALUES ('$name_permission')";
        if(mysqli_query($conn, $sql)) {
            $id_permission = mysqli_insert_id($conn);

            foreach($_POST['function'] as $id_function => $actions) {
                foreach($actions as $action) {
                    $sql = "INSERT INTO detail_permission (function_id, permission_id, action) VALUES ('$id_function', '$id_permission', '$action')";
                    mysqli_query($conn, $sql);
                }
            }
            echo "Lưu nhóm quyền thành công!";
        } else {
            echo "Lỗi khi lưu nhóm quyền: " . mysqli_error($conn);
        }
        
    }
}
?>