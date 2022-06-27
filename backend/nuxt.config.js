import colors from 'vuetify/es5/util/colors'
export default {
    // Global page headers: https://go.nuxtjs.dev/config-head
    head: {
        titleTemplate: '%s - ' + process.env.APP_NAME,
        title: process.env.APP_NAME,
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            { hid: 'description', name: 'description', content: '' },
            { name: 'format-detection', content: 'telephone=no' }
        ],
        link: [
            { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
        ]
    },

    // Global CSS: https://go.nuxtjs.dev/config-css
    css: [
        '@/assets/css/main.css',
        "@assets/fonts/Voice/font.css"
    ],

    // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
    plugins: [
        '~plugins/filters.js',
        { src: '~/plugins/client-only.js', mode: 'client' },
    ],

    // Auto import components: https://go.nuxtjs.dev/config-components
    components: true,

    // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
    buildModules: [
        // https://go.nuxtjs.dev/vuetify
        // '@nuxtjs/tailwindcss',
        '@nuxt/postcss8',
        '@nuxtjs/vuetify',
    ],

    // Modules: https://go.nuxtjs.dev/config-modules
    modules: [
        // '@nuxtjs/tailwindcss',
        // https://go.nuxtjs.dev/axios
        '@nuxtjs/axios',
        // https://go.nuxtjs.dev/pwa
        '@nuxtjs/pwa',
        '@nuxtjs/auth-next',
        '@blokwise/dynamic'
    ],
    router: {
        middleware: ['auth']
    },

    // Axios module configuration: https://go.nuxtjs.dev/config-axios
    axios: {
        // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
        //baseURL: '/',
        baseURL: process.env.API_HOST,
    },

    // PWA module configuration: https://go.nuxtjs.dev/pwa
    pwa: {
        manifest: {
            lang: 'en'
        }
    },

    // Vuetify module configuration: https://go.nuxtjs.dev/config-vuetify
    vuetify: {
        customVariables: ['~/assets/variables.scss'],
        theme: {
            light: true,
            themes: {
                dark: {
                    primary: colors.blue.darken2,
                    accent: colors.grey.darken3,
                    secondary: colors.amber.darken3,
                    info: colors.teal.lighten1,
                    warning: colors.amber.base,
                    error: colors.deepOrange.accent4,
                    success: colors.green.accent3,
                    lighter: colors.grey.base,
                    background1: colors.indigo.darken4,
                    background2: '#1e2337',
                    background3: '#060c24',
                    background4: '#262b3f',
                    background5: '#33384b',
                },
                light: {
                    primary: colors.indigo.base,
                    secondary: colors.blue.base,
                    accent: colors.deepPurple.base,
                    error: colors.red.base,
                    warning: colors.orange.base,
                    info: colors.cyan.base,
                    success: colors.lightGreen.base,
                    lighter: colors.indigo.lighten5,
                    background1: colors.indigo.lighten5,
                    background2: '#e9e9e9',
                    background3: '#f7f7f7',
                    background4: '#fdfdfd',
                    background5: '#ffffff'
                }
            }
        },
        icons: {
            iconfont: 'mdiSvg',
        },
    },

    // Build Configuration: https://go.nuxtjs.dev/config-build
    build: {
        postcss: {
            plugins: {
                tailwindcss: {},
                autoprefixer: {},
            },
        },
    },

    auth: {
        strategies: {
            local: {
                token: {
                    property: 'token',
                    global: true,
                    required: true,
                    type: 'Bearer'
                },
                user: {
                    property: 'user',
                    // autoFetch: true
                },
                endpoints: {
                    login: { url: '/login', method: 'post' },
                    logout: { url: '/logout', method: 'post' },
                    user: { url: '/get_user', method: 'get', token: 'token' }
                }
            }
        },
        redirect: {
            login: '/login',
            logout: '/login',
            home: '/'
        }
    },
    serverMiddleware: ['~/server-middleware/selectiveSSR'],
    publicRuntimeConfig: {
        baseURL: process.env.ADMIN_URL || 'http://localhost:3000',
        apiBaseURL: process.env.API_BASEURL || 'http://api.news.com'
    },
}