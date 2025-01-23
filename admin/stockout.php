<div>
    <?php
    include("../db/dbconn.php");
    $id = $_GET['id'];

    // Fetch sizes and stock for the given product ID
    $result = $conn->query("SELECT * FROM stock WHERE product_id = '$id'") or die(mysqli_error($conn));

    if ($result->num_rows > 0) {
        $sizes = [];
        while ($row = $result->fetch_assoc()) {
            $sizes[] = $row;
        }
    } else {
        $sizes = [];
    }
    ?>
    <div class="login_title"><span>Stock OUT</span></div>
    <br>
    <form method="post">
        <table class="login">
            <tr>
                <td>
                    <input type="hidden" name="pid" autocomplete="off" class="input-large number" id="text" value="<?php echo $id; ?>" required/>
                </td>
            </tr>
            <?php if (!empty($sizes)): ?>
                <?php foreach ($sizes as $size): ?>
                    <tr>
                        <td>
                            <label>
                                Size: <b><?php echo $size['product_size']; ?></b> | 
                                Available Stock: <b><?php echo $size['qty']; ?></b>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="number" name="size[<?php echo $size['product_size']; ?>]" class="input-large number" id="text" min="0" max="<?php echo $size['qty']; ?>" placeholder="Enter Quantity to Remove" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td>No stock available for this product.</td>
                </tr>
            <?php endif; ?>
            <tr>
                <td>
                    <button name="stockout" type="submit" class="btn btn-block btn-large btn-info">
                        <i class="icon-ok-sign icon-white"></i> Save Data
                    </button>
                </td>
            </tr>
        </table>
    </form>
</div>
