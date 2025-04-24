<?php 
// Start session or include code for fetching teacher details from the database
session_start();
include('dbconnect.php');

// Assuming teacher ID is stored in the session
$teacher_id = $_SESSION['id'] ?? 0; 

// Fetch teacher details from the database
if ($teacher_id) {
    $query = "SELECT name, email, phone_no, dept_name, profile_pic FROM teacher WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
        // Assign values from database
        $name = $teacher['name'] ?? 'Default Name';
        $email = $teacher['email'] ?? '';
        $phone_no = $teacher['phone_no'] ?? '';
        $dept_name = $teacher['dept_name'] ?? '';
        $profile_pic = $teacher['profile_pic'] ?? 'profile.jpg';
    } else {
        // If no result found, assign default values
        $name = 'Default Name';
        $email = '';
        $phone_no = '';
        $dept_name = '';
        $profile_pic = 'profile.jpg';
    }
} else {
    // If teacher ID is not found, assign default values
    $name = 'Default Name';
    $email = '';
    $phone_no = '';
    $dept_name = '';
    $profile_pic = 'profile.jpg';
}
?>

<?php include('header_sidebar.php'); ?>

<div class="container mt-4">
    <h3>Edit Profile</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="d-flex flex-column align-items-center mb-4">
            <img id="profile-img" src="profile_pics/<?php echo $profile_pic; ?>" class="rounded-circle" width="100" height="100">
            <h3><?php echo htmlspecialchars($name); ?></h3>
            <h4>Teacher Id: <?php echo htmlspecialchars($teacher_id); ?></h4>
            <button class="btn btn-primary mt-3" onclick="document.getElementById('profile-pic-input').click(); return false;">Change Photo</button>
            <input type="file" id="profile-pic-input" name="profile_pic" style="display:none;">
        </div>

        <label>Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        
        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        
        <label>Phone No</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone_no); ?>" required>
        
        <label>Department Name</label>
        <input type="text" name="dept_name" value="<?php echo htmlspecialchars($dept_name); ?>" required>
        
        <label>Password</label>
        <input type="password" name="password" placeholder="Leave blank to keep unchanged">
        
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>

<?php include('footer.php'); ?>
