<?php if (isset($model)) : ?>
<tr>
    <td colspan="<?php echo (1+count($group)); ?>"><b><?php echo CHtml::encode($name); ?></b> - <?php echo CHtml::encode($path); ?></td>
</tr>
<?php foreach($model as $row) : ?>
<?php if (is_array($row['method']) && count($row['method']) >= 1) : ?>
<tr>
    <td><?php echo CHtml::encode($row['class_name']);?></td>
    <?php foreach($group as $g) : ?>
    <td><?php echo CHtml::encode($g->name); ?></td>
    <?php endforeach; ?>
</tr>
<?php foreach($row['method'] as $method) : ?>
<?php
$class = str_replace('Controller', "", $row['class_name']);
$route = ($row['class_route'] != "" ? $row['class_route'] . "/" : "") . $class . "/" . $method;
?>
<tr>
    <td width="200">
        <?php echo CHtml::encode($route); ?>
    </td>
    <?php foreach($group as $g) : ?>
    <?php
    $data = array(
        'user_group' => $g->id,
        'route' => $route,
    );
    ?>
    <td align="center"><input name="Access[route][<?php echo $g->id; ?>][]" type="checkbox" <?php echo $this->AclSelected($acl, $g->id, $route); ?> value='<?php echo $name; ?>-<?php echo CHtml::encode($route); ?>' /></td>
    <?php endforeach; ?>
</tr>
<?php endforeach ?>
<?php endif ?>
<?php endforeach; ?>
<?php else : ?>
not found
<?php endif; ?>
