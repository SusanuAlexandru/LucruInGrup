<?php
interface ElevRepositoryInterface
{
    public function createElev($nume_prenume, $email, $clasa, $data_nasterii, $nr_parinte);
    public function readElevi();
    public function updateElev($id, $nume_prenume, $email, $clasa, $data_nasterii, $nr_parinte);
    public function deleteElev($id);
}
