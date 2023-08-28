class HttpClient {
    private readonly env = useRuntimeConfig().public as any;
    
    protected url?: string;

    protected baseUrl?: string;

    protected headers?: any;

    protected data?: any;

    protected params?: any;

    protected method: 'get'|'post'|'put'|'patch'|'delete'|'PATCH' = 'get';

    public setUrl(url: string, baseUrl?: string): void {
        this.url = url;

        if (baseUrl) {
            this.baseUrl = baseUrl;
        }
    }

    public setParams (params: any): void {
        this.params = params;
    }

    public setMethod (method: 'get'|'post'|'put'|'patch'|'delete'|'PATCH'): void {
        this.method = method;
    }

    public setData (data: any): void {
        this.data = data;
    }

    public setHeaders (headers: any): void {
        this.headers = headers;
    }

    protected reset (): void {
        this.url = undefined;
        this.baseUrl = undefined;
        this.headers = undefined;
        this.data = undefined;
        this.params = undefined;
        this.method = 'get';
    }

    public async fetch (): Promise<any> {
        let url = this.url as string;
        
        if (typeof this.params !== 'undefined') {
            url += '?' + (new URLSearchParams(this.params)).toString();
        }

        let headers: any = {
            'Content-Type': 'application/json; charset=utf-8',
            Accept: 'application/json; charset=utf-8',
            'Access-Control-Allow-Origin': '*',
        };

        if (typeof this.headers !== 'undefined') {
            headers = { ...headers, ...this.headers };
        }

        try {
            const res = await $fetch(url, {
                mode: 'cors',
                method: this.method,
                baseURL: typeof this.baseUrl !== 'undefined' ? this.baseUrl : this.env.api.baseUrl,
                headers,
                body: typeof this.data === 'undefined' ? undefined : JSON.stringify(this.data),
                retry: 0,
            });

            this.reset();

            return res;
        } catch (e) {
            // Handle expected errors, such 403 in case of auth implementation
            throw e;
        }
    }
}

export default HttpClient;