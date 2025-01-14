<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function index()
    {
        $this->load->model('DashboardModel');

        $data['total_customers'] = $this->DashboardModel->getTotalCustomers();
        $data['total_transactions'] = $this->DashboardModel->getTotalTransactions();
        $data['total_income'] = $this->DashboardModel->getTotalIncome();
        $data['total_menus'] = $this->DashboardModel->getTotalMenus();
        $data['menu_statistics'] = $this->DashboardModel->getMenuStatistics();
        $data['detailed_transactions'] = $this->DashboardModel->getDetailedTransactions();

        $monthly_data = $this->DashboardModel->getMonthlyEarnings();
        $data['monthly_labels'] = array_map(function ($item) {
            return date('F', mktime(0, 0, 0, $item['month'], 1));
        }, $monthly_data);
        $data['monthly_earnings'] = array_column($monthly_data, 'earnings');    

        $this->load->view('dashboard', $data);
    }
}