<!-- Edit profile form -->
<form method="post" action="editprofile.php" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
  </div>
  <div class="mb-3">
    <label for="gender" class="form-label">Gender:</label>
    <select class="form-select" id="gender" name="gender">
        <option value="Male" <?php if ($gender === "Male") echo "selected"; ?>>Male</option>
        <option value="Female" <?php if ($gender === "Female") echo "selected"; ?>>Female</option>
    </select>
  </div>
  <div class="mb-3">
    <label for="age" class="form-label">Age</label>
    <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>">
  </div>
  <div class="mb-3">
    <label for="preferred_location" class="form-label">Preferred Location</label>
    <input type="text" class="form-control" id="preferred_location" name="preferred_location" value="<?php echo htmlspecialchars($preferred_location); ?>">
  </div>
  <!-- Profile Picture Section -->
  <div class="mb-3">
    <label for="profilePicture" class="form-label">Change Profile Picture</label>
    <input class="form-control" type="file" id="profilePicture" name="profilePicture" accept="image/*">
  </div>

  <button type="submit" class="btn btn-primary">Save Changes</button>
</form>
