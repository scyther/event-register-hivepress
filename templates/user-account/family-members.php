<?php
echo '<table>';

echo '<tr>';
echo '<td> Name </td>';
echo '<td> Age </td>';
echo '<td> Relation </td>';
echo '</tr>';
foreach ($family_members as $member) {

  echo '<tr>';
  echo '<td>' . $member->get_member_name() . '</td>';
  echo '<td>' . $member->get_age() . '</td>';
  echo '<td>' . $member->get_relation() . '</td>';
  echo '</tr>';
}
echo '</table>';
?>