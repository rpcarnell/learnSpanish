<?php
class castingVars
{
    public function __construct()
    {
        
    }
    public function lookingFor($index)
    {
        $looking[0] = "Professionals Only";
        $looking[1] = "Professionals & Non Professionals";
        $looking[2] = "Non-Professionals Only";
        return $looking[$index];
    }
    public function genders($index)
    {
        $gender[0] = "Males & Females";
        $gender[1] = "Females";
        $gender[0] = "Males";
        return $gender[$index];
    }
    public function ethnicities($index)
    {
         $etnies[0] = "All";
         $etnies[1] = "Caucasian";
         $etnies[2] = "African American";
         $etnies[3] = "Hispanic";
         $etnies[4] = "Asian";
         $etnies[5] = "South Asian";
         $etnies[6] = "Native American";
         $etnies[7] = "Middle Eastern";
         $etnies[8] = "Southeast Asian / Pacific Islander";
         $etnies[9] = "Ethnically Ambiguous / Mixed Race";
         $etnies[10] = "African Descent";
         return $etnies[$index];
    }
    public function auditSubmit($index)
    {
        $auditsubmit[0] = "Yes, I am scheduling auditions";
        $auditsubmit[1] = "No, I am looking for submissions only";
        return $auditsubmit[$index];
    }
    public function union($index)
    {
        $union[0] = "union";
        $union[1] = "non-union";
        $union[2] = "union and non-union";
        return $union[$index];
    }
    public function paid($index)
    {
        $paid[0] = "paid";
        $paid[1] = "unpaid";
        $paid[2] = "paid and unpaid";
        return $paid[$index];
    }
}
?>