import { GraphqlResponseError } from "@octokit/graphql"; // Testing GraphQL Queries: https://docs.github.com/en/graphql/overview/explorer
import { Organization, Repository } from "@octokit/graphql-schema"; // https://www.npmjs.com/package/@octokit/graphql-schema
import { Elysia, t } from "elysia"; // https://elysiajs.com/introduction.html
import { Octokit } from "octokit"; // { App } // https://github.com/octokit/octokit.js
import 'dotenv/config'; // process.env.<ENV_VAR_NAME>
import { GITHUB_ORGANIZATION_BY_NAME } from "./github_graphql_queries";

const GITHUB_PAT = process.env.GITHUB_PAT;
const octokit = new Octokit({ auth: GITHUB_PAT });

export const GITHUB_URLS = new Elysia({ prefix: '/url' })
    .get('/orgs/:organization_name/projects/:project_id/views/:project_view', async (
        { params: { organization_name, project_id, project_view }, set }) => {
        try {
            const { // TODO
                organization,
            } = await octokit.graphql<{ organization: Organization }>(`{
                organization(login: "${organization_name}") {
                    name
                    description
                    url
                    projectV2(number: ${project_id}) {
                        number
                        title
                        url
                        closed
                        views(first: ${project_view}) {
                            totalCount
                            nodes {
                                name
                            }
                        }
                    }
                }
            }`);

            let error = false;
            const projectViewNumber = Number(project_view);
            let views = organization?.projectV2?.views;
            const totalViews = organization?.projectV2?.views?.totalCount ?? 0;
            if (projectViewNumber < 1) {
                error = true;
            }
            
            // remove all views except the one with the given view number
            if (totalViews > 0 && views && views.nodes && (views?.nodes?.length ?? 0 > 0)) {
                if (projectViewNumber > totalViews) {
                    error = true;
                } else {
                    views.nodes = [views.nodes[projectViewNumber - 1]];
                }
            } else {
                error = true;
            }

            if (error) {
                set.status = 404;
                set.headers['Content-Type'] = 'text/plain';
                return { error: 404, message: 'Not Found' };
            }

            return JSON.stringify(organization, null, 2);
        } catch (error) {
            if (error instanceof GraphqlResponseError) {
                console.log("Request failed:", error.request);
                console.log(error.message);
                return error;
            } else {
                return error;
            }
        }
    }, {
        params: t.Object({
            organization_name: t.String(),
            project_id: t.String(),
            project_view: t.String()
        })
    });

export const GITHUB_ORGANIZATION = new Elysia({ prefix: '/organization' })
    .get('/:organization_name', async ({ params: { organization_name } }) => {
        try {
            const { // TODO: this is only a preview, final will be only root level info (tested, to find out what is available for this and other endpoints)
                organization,
            } = await octokit.graphql<{ organization: Organization }>(GITHUB_ORGANIZATION_BY_NAME(organization_name));

            return JSON.stringify(organization, null, 2);
        } catch (error) {
            if (error instanceof GraphqlResponseError) {
                console.log("Request failed:", error.request);
                console.log(error.message);
                return error;
            } else {
                return error;
            }
        }
    }, {
        params: t.Object({
            organization_name: t.String()
        })
    })
    .get('/:organization_name/repository/:repository_name', async (
        { params: { organization_name, repository_name } }) => {
        try {
            const { // TODO
                organization,
            } = await octokit.graphql<{ organization: Organization }>(`{
                organization(login: "${organization_name}") {
                    name
                    description
                    url
                    repository(name: "${repository_name}") {
                        name
                        languages(first: 10) {
                            nodes {
                                name
                            }
                        }
                        open_issues: issues(first: 50, states: [OPEN]) {
                            totalCount
                            nodes {
                                title
                            }
                        }
                        closed_issues: issues(first: 50, states: [CLOSED]) {
                            totalCount
                            nodes {
                                title
                            }
                        }
                    }
                }
            }`);

            return JSON.stringify(organization, null, 2);
        } catch (error) {
            if (error instanceof GraphqlResponseError) {
                console.log("Request failed:", error.request);
                console.log(error.message);
                return error;
            } else {
                return error;
            }
        }
    }, {
        params: t.Object({
            organization_name: t.String(),
            repository_name: t.String()
        })
    })
    .get('/:organization_name/repository/:repository_name/project/:project_name', async (
        { query, params: { organization_name, repository_name, project_name } }) => {
        try {
            const { // TODO
                repository,
            } = await octokit.graphql<{ repository: Repository }>(`{
                repository(owner: "${organization_name}", name: "${repository_name}") {
                        name
                        description
                        url
                        projectsV2(query: "${project_name}", first: 1) {
                        nodes {
                            title
                            shortDescription
                            url
                            public
                            createdAt
                            updatedAt
                            teams(first: 4) {
                                nodes {
                                    name
                                    members(first: 32) {
                                        nodes {
                                            name
                                        }
                                    }
                                }
                            }
                            items(first: 100) {
                                totalCount
                            }
                            view(number: ${(query?.view ?? 1)}) {
                                name
                                filter
                            }
                        }
                    }
                }
            }`);

            return JSON.stringify(repository, null, 2);
        } catch (error) {
            if (error instanceof GraphqlResponseError) {
                console.log("Request failed:", error.request);
                console.log(error.message);
                return error;
            } else {
                return error;
            }
        }
    }, {
        params: t.Object({
            organization_name: t.String(),
            repository_name: t.String(),
            project_name: t.String()
        })
    });
