<?php

// $familyMembers = array(
//   'John Doe',
//   'Jane Doe',
//   'Tom Smith',
//   'Emily Johnson'
// );

echo '<ul>';
foreach ($family_members as $member) {
  echo '<li>' . $member->get_id() . '</li>';
}
echo '</ul>';

?>