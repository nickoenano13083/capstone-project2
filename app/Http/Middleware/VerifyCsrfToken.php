{{ ... }}
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/events/*/check-in',
    ];
{{ ... }}
