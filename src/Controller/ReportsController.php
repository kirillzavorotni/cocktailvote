<?php


class ReportsController extends CommonController
{
    public function getVotesReport()
    {
        $report = new ReportsModel();
        $report->getVotesReport();
    }
}