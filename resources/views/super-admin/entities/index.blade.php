@extends('layouts.super-admin.app')

@section('title', \App\CPU\translate('Super Admin') . ' | Entities')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-3 mb-3 border-0">
            <div class="flex-between align-items-center">
                <h1 class="page-header-title mb-0"><b>Entities</b> <span class="text-muted h5">(tenants)</span></h1>
                <a href="{{ route('super-admin.entities.create') }}" class="btn btn--primary">
                    <i class="tio-add"></i>
                    <span class="text">{{ \App\CPU\translate('Add new') }}</span>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>{{ \App\CPU\translate('Name') }}</th>
                            <th>{{ \App\CPU\translate('Phone') }}</th>
                            <th>{{ \App\CPU\translate('email') }}</th>
                            <th>Domains / {{ \App\CPU\translate('tenant access') }}</th>
                            <th>{{ \App\CPU\translate('Created at') }}</th>
                            <th class="text-right">{{ \App\CPU\translate('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($entities as $entity)
                            <tr>
                                <td><code>{{ $entity->id }}</code></td>
                                <td>{{ $entity->name }}</td>
                                <td>{{ data_get($tenantAdmins, $entity->id . '.phone') ?: '-' }}</td>
                                <td>{{ data_get($tenantAdmins, $entity->id . '.email') ?: '-' }}</td>
                                <td>
                                    @foreach($entity->domains as $domain)
                                        @php
                                            $hostWithPort = $domain->domain;
                                            $port = request()->getPort();
                                            if ($port && ! in_array((int) $port, [80, 443], true)) {
                                                $hostWithPort .= ':'.$port;
                                            }
                                            $tenantAdminLogin = request()->getScheme().'://'.$hostWithPort.'/admin/auth/login';
                                        @endphp
                                        <div class="mb-1">
                                            <span class="badge badge-soft-info">{{ $domain->domain }}</span>
                                            <a href="{{ $tenantAdminLogin }}" class="btn btn-sm btn-outline-primary ml-2"
                                               target="_blank" rel="noopener noreferrer">
                                                {{ \App\CPU\translate('Admin login') }}
                                            </a>
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ $entity->created_at }}</td>
                                <td class="text-right">
                                    <form class="d-inline" method="post"
                                          action="{{ route('super-admin.entities.destroy', $entity) }}"
                                          onsubmit="return confirm('{{ \App\CPU\translate('Are you sure') }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            {{ \App\CPU\translate('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">{{ \App\CPU\translate('No data found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">{{ $entities->links() }}</div>
        </div>
    </div>
@endsection
