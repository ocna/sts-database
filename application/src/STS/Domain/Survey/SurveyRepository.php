<?php
namespace STS\Domain\Survey;

interface SurveyRepository
{
    public function save($survey);

    public function load($id);

    public function delete($id);
}
