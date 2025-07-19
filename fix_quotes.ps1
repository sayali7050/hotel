# Fix remaining quote issues in all view files
$viewFiles = Get-ChildItem "application/views/*.php"

foreach ($file in $viewFiles) {
    Write-Host "Fixing quotes in $($file.Name)..."
    
    $content = Get-Content $file.FullName -Raw
    
    # Fix missing closing quotes in href attributes
    $content = $content -replace 'href="<?php echo base_url\(''([^'']+)''\) ?>"', 'href="<?php echo base_url(''$1'') ?>"'
    
    # Fix missing closing quotes in src attributes
    $content = $content -replace 'src="<?php echo base_url\(''([^'']+)''\) ?>"', 'src="<?php echo base_url(''$1'') ?>"'
    
    # Fix missing closing quotes in script src attributes
    $content = $content -replace '<script src="<?php echo base_url\(''([^'']+)''\) ?>">', '<script src="<?php echo base_url(''$1'') ?>">'
    
    # Fix missing closing quotes in url() functions
    $content = $content -replace 'url\(<?php echo base_url\(''([^'']+)''\) ?>\)', 'url(<?php echo base_url(''$1'') ?>)'
    
    Set-Content $file.FullName $content -Encoding UTF8
    Write-Host "Fixed quotes in $($file.Name)"
}

Write-Host "All quote issues have been fixed!" 