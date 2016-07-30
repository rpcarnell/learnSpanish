<?php
if (is_array($tutors))
{  if (count($tutors) > 0) {
    foreach ($tutors as $t) { echo "<p><a style='text-decoration: none;' href='javascript:void(0)' onClick='tutorstudent(".$t->tutor_id.", \"".str_replace("'", '', $t->name)."\")'>".$t->name."</a></p>"; }
} } else echo "<p>There are no tutors for ".$user->name."</p>";
?>