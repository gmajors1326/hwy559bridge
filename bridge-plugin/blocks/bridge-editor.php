<?php
/**
 * Block Template: Bridge Inventory Editor
 * This is the anchor point for the React application.
 */

// Create a unique ID for the React mount point
$block_id = 'bridge-editor-' . $block['id'];
?>

<div id="bridge-inventory-app" class="bridge-editor-block bridge-inventory-app-mount"></div>

<style>
    .bridge-editor-block {
        min-height: 800px;
        background: #f8fafc;
        border-radius: 0;
        overflow: hidden;
    }
</style>
