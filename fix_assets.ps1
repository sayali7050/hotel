# Fix asset paths in all view files
$viewFiles = Get-ChildItem "application/views/*.php" -Exclude "index.php"

foreach ($file in $viewFiles) {
    Write-Host "Processing $($file.Name)..."
    
    $content = Get-Content $file.FullName -Raw
    
    # Fix href="assets/ to href="<?php echo base_url('assets/
    $content = $content -replace 'href="assets/', 'href="<?php echo base_url(''assets/'
    
    # Fix src="assets/ to src="<?php echo base_url('assets/
    $content = $content -replace 'src="assets/', 'src="<?php echo base_url(''assets/'
    
    # Fix url(assets/ to url(<?php echo base_url('assets/
    $content = $content -replace 'url\(assets/', 'url(<?php echo base_url(''assets/'
    
    # Fix href="index.php to href="<?php echo base_url(); ?>"
    $content = $content -replace 'href="index\.php"', 'href="<?php echo base_url(); ?>"'
    
    # Fix href="about.php to href="<?php echo base_url('about'); ?>"
    $content = $content -replace 'href="about\.php"', 'href="<?php echo base_url(''about''); ?>"'
    $content = $content -replace 'href="rooms\.php"', 'href="<?php echo base_url(''rooms''); ?>"'
    $content = $content -replace 'href="amenities\.php"', 'href="<?php echo base_url(''amenities''); ?>"'
    $content = $content -replace 'href="location\.php"', 'href="<?php echo base_url(''location''); ?>"'
    $content = $content -replace 'href="contact\.php"', 'href="<?php echo base_url(''contact''); ?>"'
    $content = $content -replace 'href="booking\.php"', 'href="<?php echo base_url(''booking''); ?>"'
    $content = $content -replace 'href="room-details\.php"', 'href="<?php echo base_url(''room-details''); ?>"'
    $content = $content -replace 'href="restaurant\.php"', 'href="<?php echo base_url(''restaurant''); ?>"'
    $content = $content -replace 'href="offers\.php"', 'href="<?php echo base_url(''offers''); ?>"'
    $content = $content -replace 'href="events\.php"', 'href="<?php echo base_url(''events''); ?>"'
    $content = $content -replace 'href="gallery\.php"', 'href="<?php echo base_url(''gallery''); ?>"'
    $content = $content -replace 'href="terms\.php"', 'href="<?php echo base_url(''terms''); ?>"'
    $content = $content -replace 'href="privacy\.php"', 'href="<?php echo base_url(''privacy''); ?>"'
    $content = $content -replace 'href="starter-page\.php"', 'href="<?php echo base_url(''starter-page''); ?>"'
    
    Set-Content $file.FullName $content -Encoding UTF8
    Write-Host "Fixed $($file.Name)"
}

Write-Host "All view files have been updated!" 