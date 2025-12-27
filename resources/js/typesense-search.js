import Typesense from 'typesense';

class TypesenseSearch {
    constructor() {
        this.client = null;
        this.initialized = false;
        this.initPromise = this.init();
    }

    async init() {
        try {
            const response = await fetch('/api/typesense/config');
            const config = await response.json();

            // Check if Typesense is enabled
            if (config.enabled === false) {
                console.log('Typesense search disabled:', config.message || 'Search suggestions unavailable');
                return;
            }

            this.client = new Typesense.Client({
                nodes: config.nodes,
                apiKey: config.api_key,
                connectionTimeoutSeconds: config.connectionTimeoutSeconds || 2
            });

            this.initialized = true;
        } catch (error) {
            console.error('Failed to initialize Typesense client:', error);
        }
    }

    async getSuggestions(query = '', limit = 8) {
        await this.initPromise;

        if (!this.initialized || !this.client) {
            console.warn('Typesense client not initialized');
            return [];
        }

        try {
            if (!query.trim()) {
                return await this.getPopularSuggestions(limit);
            }

            const suggestions = [];

            // 1. Job title suggestions
            const jobTitleResults = await this.searchJobTitles(query, Math.min(limit, 4));
            suggestions.push(...jobTitleResults);

            // 2. Company suggestions (if we still need more)
            if (suggestions.length < limit) {
                const companyResults = await this.searchCompanies(query, Math.min(limit - suggestions.length, 3));
                suggestions.push(...companyResults);
            }

            // 3. Location suggestions (if we still need more)
            if (suggestions.length < limit) {
                const locationResults = await this.searchLocations(query, Math.min(limit - suggestions.length, 2));
                suggestions.push(...locationResults);
            }

            return suggestions.slice(0, limit);
        } catch (error) {
            console.error('Error fetching Typesense suggestions:', error);
            return [];
        }
    }

    async getPopularSuggestions(limit = 8) {
        try {
            const searchResults = await this.client
                .collections('listing_index')
                .documents()
                .search({
                    q: '*',
                    query_by: 'job_title',
                    group_by: 'job_title',
                    group_limit: 1,
                    per_page: limit,
                    sort_by: 'posted_at:desc'
                });

            const suggestions = [];
            if (searchResults.grouped_hits) {
                for (const group of searchResults.grouped_hits) {
                    if (group.hits && group.hits[0]) {
                        const hit = group.hits[0];
                        suggestions.push({
                            text: hit.document.job_title,
                            type: 'popular',
                            icon: 'las la-briefcase',
                            count: group.found,
                            badge: 'Popular'
                        });
                    }
                }
            }

            return suggestions;
        } catch (error) {
            console.error('Error fetching popular suggestions:', error);
            return [];
        }
    }

    async searchJobTitles(query, limit) {
        try {
            const searchResults = await this.client
                .collections('listing_index')
                .documents()
                .search({
                    q: query,
                    query_by: 'job_title',
                    group_by: 'job_title',
                    group_limit: 1,
                    per_page: limit,
                    prefix: true,
                    sort_by: 'posted_at:desc'
                });

            const suggestions = [];
            if (searchResults.grouped_hits) {
                for (const group of searchResults.grouped_hits) {
                    if (group.hits && group.hits[0]) {
                        const hit = group.hits[0];
                        suggestions.push({
                            text: hit.document.job_title,
                            type: 'job_title',
                            icon: 'las la-briefcase',
                            count: group.found,
                            category: hit.document.job_category || null
                        });
                    }
                }
            }

            return suggestions;
        } catch (error) {
            console.error('Error searching job titles:', error);
            return [];
        }
    }

    async searchCompanies(query, limit) {
        try {
            const searchResults = await this.client
                .collections('listing_index')
                .documents()
                .search({
                    q: query,
                    query_by: 'employer_name',
                    group_by: 'employer_name',
                    group_limit: 1,
                    per_page: limit,
                    prefix: true,
                    sort_by: 'posted_at:desc'
                });

            const suggestions = [];
            if (searchResults.grouped_hits) {
                for (const group of searchResults.grouped_hits) {
                    if (group.hits && group.hits[0]) {
                        const hit = group.hits[0];
                        suggestions.push({
                            text: hit.document.employer_name,
                            type: 'company',
                            icon: 'las la-building',
                            count: group.found
                        });
                    }
                }
            }

            return suggestions;
        } catch (error) {
            console.error('Error searching companies:', error);
            return [];
        }
    }

    async searchLocations(query, limit) {
        try {
            const searchResults = await this.client
                .collections('listing_index')
                .documents()
                .search({
                    q: query,
                    query_by: 'city,state,country',
                    group_by: 'city',
                    group_limit: 1,
                    per_page: limit,
                    prefix: true,
                    sort_by: 'posted_at:desc'
                });

            const suggestions = [];
            if (searchResults.grouped_hits) {
                for (const group of searchResults.grouped_hits) {
                    if (group.hits && group.hits[0]) {
                        const hit = group.hits[0];
                        let locationText = hit.document.city || '';
                        if (hit.document.state) {
                            locationText += ', ' + hit.document.state;
                        }
                        if (hit.document.country) {
                            locationText += ', ' + hit.document.country;
                        }

                        suggestions.push({
                            text: locationText,
                            type: 'location',
                            icon: 'las la-map-marker',
                            count: group.found
                        });
                    }
                }
            }

            return suggestions;
        } catch (error) {
            console.error('Error searching locations:', error);
            return [];
        }
    }
}

window.typesenseSearch = new TypesenseSearch();

export default TypesenseSearch;
