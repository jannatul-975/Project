<?php 
session_start();
include('../../../db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../../logout.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

$query = "SELECT name, email, phone, dept_name, profile_pic FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h4 style='color:red;'>Teacher profile not found.</h4>";
    exit();
}

$teacher = $result->fetch_assoc();
$name = $teacher['name'];
$email = $teacher['email'];
$phone_no = $teacher['phone'];
$dept_name = $teacher['dept_name'];
$profile_pic = $teacher['profile_pic'] ?: 'profile.jpg';
?>

<div class="container">
    <h3>Edit Profile</h3>
    <form method="POST" enctype="form-data">
        <div class="profile">
            <img id="profile-img" src="profile_pics/<?php echo htmlspecialchars($profile_pic); ?>" class="picture" width="100" height="100">
            <h3><?php echo htmlspecialchars($name); ?></h3>
            <button class="button" onclick="document.getElementById('profile-pic-input').click(); return false;">Change Photo</button>
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
        
        <button type="submit" class="buttonSuccess">Update</button>
    </form>
</div>
