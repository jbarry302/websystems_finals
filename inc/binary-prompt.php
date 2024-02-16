<?php
/**
 * Displays a custom modal that prompts the user for a binary choice (yes/no).
 * @author jbarry302
 *
 * @param bool $conditionSet to true to show the modal
 * @param string $headerMsg The header message of the modal
 * @param string $paragraphMsg The paragraph message of the modal
 * @param string $positiveBtn The positive button of the modal, defaults to a "Yes" button that redirects to index.php
 * @param string $negativeBtn The negative button of the modal, defaults to a "No" button that redirects to login.php
 *
 * Usage:
 *     $condition = true;
 *     $headerMsg = "This is an important message";
 *     $paragraphMsg = "You need to choose between the two options";
 *     $positiveBtn = '<a href="yes.php" class="btn">Yes</a>';
 *     $negativeBtn = '<a href="no.php" class="btn">No</a>';
 *     include('inc/binary-prompt.php');
 */
if (!empty($condition)) : ?>
    <?php if ($condition) : ?>
        <div class="modal-backdrop">
            <div class="modal-content">
                <?php
                if (empty($headerMsg)) $headerMsg = "Candy Needed";
                if (empty($paragraphMsg)) $paragraphMsg = "Do you want a candy?";
                if (empty($postiveBtn)) $postiveBtn = '<a href="index.php" class="btn">Yes</a>';
                if (empty($negativeBtn)) $negativeBtn = '<a href="login.php" class="btn">No</a>';
                ?>
                <h2><?= $headerMsg ?></h2>
                <p><?= $paragraphMsg ?></p>
                <div class="modal-buttons">
                    <?= $postiveBtn ?>
                    <?= $negativeBtn ?>
                </div>
            </div>
        </div>
        <link rel="stylesheet" href="assets/css/strict-modal.css">
    <?php endif ?>
<?php endif ?>