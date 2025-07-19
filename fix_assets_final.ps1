# Final fix for asset and image links in all view files
$viewFiles = Get-ChildItem "application/views/*.php"

foreach ($file in $viewFiles) {
    Write-Host "Fixing asset links in $($file.Name)..."
    $content = Get-Content $file.FullName -Raw

    # Fix href attributes
    $content = $content -replace 'href="<\?php echo base_url\(''([^'']+)''\)" rel="([^"]+)"', 'href="<?php echo base_url(''$1''); ?>" rel="$2"'
    $content = $content -replace 'href="<\?php echo base_url\(''([^'']+)''\)"', 'href="<?php echo base_url(''$1''); ?>"'
    $content = $content -replace 'href="<\?php echo base_url\(([^\)]+)\)', 'href="<?php echo base_url($1); ?>"'

    # Fix src attributes
    $content = $content -replace 'src="<\?php echo base_url\(''([^'']+)''\)"', 'src="<?php echo base_url(''$1''); ?>"'
    $content = $content -replace 'src="<\?php echo base_url\(([^\)]+)\)', 'src="<?php echo base_url($1); ?>"'

    # Fix url() in style attributes
    $content = $content -replace 'url\(<\?php echo base_url\(''([^'']+)''\)\)', 'url(<?php echo base_url(''$1''); ?>)'
    $content = $content -replace 'url\(<\?php echo base_url\(([^\)]+)\)\)', 'url(<?php echo base_url($1); ?>)'

    # Remove any double semicolons
    $content = $content -replace '; ;', ';'

    Set-Content $file.FullName $content -Encoding UTF8
    Write-Host "Fixed $($file.Name)"
}

Write-Host "All asset and image links have been fixed!" 