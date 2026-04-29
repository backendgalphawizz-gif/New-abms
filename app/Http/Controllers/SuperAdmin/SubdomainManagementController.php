<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Application;
use App\Model\ApplicationPaymentDetail;
use App\Model\Currency;
use App\Model\Order;
use App\Model\Product;
use App\Model\Seller;
use App\Models\Entity;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SubdomainManagementController extends Controller
{
    public function dashboard(string $entityId): View
    {
        $entity = $this->resolveEntity($entityId);
        $pageTitle = 'Super Admin | '.$entity->name.' | '.__('Dashboard');

        $payload = $entity->run(function () {
            $hasApplications = Schema::hasTable('applications');
            $hasOrders = Schema::hasTable('orders');
            $hasUsers = Schema::hasTable('users');
            $hasAdmins = Schema::hasTable('admins');
            $hasProducts = Schema::hasTable('products');
            $hasSellers = Schema::hasTable('sellers');

            $data = [
                'total_application' => $hasApplications ? Application::count() : 0,
                'pending_application' => $hasApplications ? Application::where('is_accept', 0)->count() : 0,
                'complete_application' => $hasApplications ? Application::where('status', 'complete')->count() : 0,
                'rejected_application' => $hasApplications ? Application::where('is_accept', 2)->count() : 0,
                'total_customer' => $hasUsers ? User::count() : 0,
                'total_quality' => $hasAdmins ? Admin::where('admin_role_id', 2)->count() : 0,
                'total_assessor' => $hasAdmins ? Admin::where('admin_role_id', 3)->count() : 0,
                'total_accreditation' => $hasAdmins ? Admin::where('admin_role_id', 5)->count() : 0,
                'total_orders' => $hasOrders ? Order::count() : 0,
                'total_products' => $hasProducts ? Product::count() : 0,
                'total_sellers' => $hasSellers ? Seller::count() : 0,
            ];

            if ($hasApplications && Schema::hasTable('application_payment_details')) {
                $data['total_earning'] = ApplicationPaymentDetail::query()->selectRaw("
                        SUM(CASE WHEN application_fee_status = 1 THEN application_fee ELSE 0 END) +
                        SUM(CASE WHEN document_fee_status = 1 THEN document_fee ELSE 0 END) +
                        SUM(CASE WHEN assessment_fee_status = 1 THEN assessment_fee ELSE 0 END) +
                        SUM(CASE WHEN technical_assessment_fee_status = 1 THEN technical_assessment_fee ELSE 0 END)
                        AS total_earning
                    ")->value('total_earning');
                $data['pending_amount'] = ApplicationPaymentDetail::query()->selectRaw("
                        SUM(CASE WHEN application_fee_status = 0 THEN application_fee ELSE 0 END) +
                        SUM(CASE WHEN document_fee_status = 0 THEN document_fee ELSE 0 END) +
                        SUM(CASE WHEN assessment_fee_status = 0 THEN assessment_fee ELSE 0 END) +
                        SUM(CASE WHEN technical_assessment_fee_status = 0 THEN technical_assessment_fee ELSE 0 END)
                        AS pending_amount
                    ")->value('pending_amount');
            } else {
                $data['total_earning'] = 0;
                $data['pending_amount'] = 0;
            }

            $iso = Helpers::get_business_settings('entity_iso_certifications');

            $currencySymbol = '';
            if (Schema::hasTable('currencies')) {
                try {
                    $currencyId = Helpers::get_business_settings('system_default_currency');
                    $currency = Currency::query()->where('id', $currencyId)->first();
                    $currencySymbol = $currency->symbol ?? '';
                } catch (\Throwable $e) {
                    $currencySymbol = '';
                }
            }

            return [
                'data' => $data,
                'iso_certifications' => is_array($iso) ? $iso : [],
                'currency_symbol' => $currencySymbol,
            ];
        });

        return view('super-admin.subdomains.inspect.dashboard', [
            'entity' => $entity,
            'pageTitle' => $pageTitle,
            'data' => $payload['data'],
            'isoCertifications' => $payload['iso_certifications'],
            'currencySymbol' => $payload['currency_symbol'],
        ]);
    }

    public function applications(string $entityId): View
    {
        $entity = $this->resolveEntity($entityId);
        $pageTitle = 'Super Admin | '.$entity->name.' | '.__('Applications');

        $applications = $entity->run(function () {
            if (! Schema::hasTable('applications')) {
                return null;
            }

            return Application::query()
                ->with('user')
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();
        });

        return view('super-admin.subdomains.inspect.applications', compact('entity', 'pageTitle', 'applications'));
    }

    public function orders(string $entityId): View
    {
        $entity = $this->resolveEntity($entityId);
        $pageTitle = 'Super Admin | '.$entity->name.' | '.__('Orders');

        $orders = $entity->run(function () {
            if (! Schema::hasTable('orders')) {
                return null;
            }

            return Order::query()
                ->with('customer')
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();
        });

        return view('super-admin.subdomains.inspect.orders', compact('entity', 'pageTitle', 'orders'));
    }

    public function products(string $entityId): View
    {
        $entity = $this->resolveEntity($entityId);
        $pageTitle = 'Super Admin | '.$entity->name.' | '.__('Products');

        $products = $entity->run(function () {
            if (! Schema::hasTable('products')) {
                return null;
            }

            return Product::query()
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();
        });

        return view('super-admin.subdomains.inspect.products', compact('entity', 'pageTitle', 'products'));
    }

    public function customers(string $entityId): View
    {
        $entity = $this->resolveEntity($entityId);
        $pageTitle = 'Super Admin | '.$entity->name.' | '.__('Customers');

        $customers = $entity->run(function () {
            if (! Schema::hasTable('users')) {
                return null;
            }

            return User::query()
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();
        });

        return view('super-admin.subdomains.inspect.customers', compact('entity', 'pageTitle', 'customers'));
    }

    public function sellers(string $entityId): View
    {
        $entity = $this->resolveEntity($entityId);
        $pageTitle = 'Super Admin | '.$entity->name.' | '.__('Sellers');

        $sellers = $entity->run(function () {
            if (! Schema::hasTable('sellers')) {
                return null;
            }

            return Seller::query()
                ->with('shop')
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();
        });

        return view('super-admin.subdomains.inspect.sellers', compact('entity', 'pageTitle', 'sellers'));
    }

    public function employees(string $entityId): View
    {
        $entity = $this->resolveEntity($entityId);
        $pageTitle = 'Super Admin | '.$entity->name.' | '.__('Employees');

        $employees = $entity->run(function () {
            if (! Schema::hasTable('admins')) {
                return null;
            }

            return Admin::query()
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();
        });

        return view('super-admin.subdomains.inspect.employees', compact('entity', 'pageTitle', 'employees'));
    }

    private function resolveEntity(string $entityId): Entity
    {
        /** @var Entity $entity */
        $entity = Entity::query()->where('id', $entityId)->firstOrFail();

        return $entity;
    }
}
