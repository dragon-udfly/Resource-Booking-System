use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CheckDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $dbConnectionStatus = true;
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbConnectionStatus = false;
        }

        View::share('db_connection_status', $dbConnectionStatus);

        return $next($request);
    }
}
