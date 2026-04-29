<?php

declare(strict_types=1);

/*
| Tenant (subdomain) web routes — same URIs as before, scoped per entity DB.
*/

require base_path('routes/web.php');
require base_path('routes/customer.php');
require base_path('routes/seller.php');
require base_path('routes/admin.php');
require base_path('routes/shared.php');
