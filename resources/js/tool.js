Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'nova-artisan',
            path: '/nova-artisan',
            component: require('./components/Tool'),
        },
    ])
})
