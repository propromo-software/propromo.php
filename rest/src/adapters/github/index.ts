import { Organization, Repository } from "@octokit/graphql-schema"; // https://www.npmjs.com/package/@octokit/graphql-schema
import { Elysia, t } from "elysia"; // https://elysiajs.com/introduction.html
import 'dotenv/config'; // process.env.<ENV_VAR_NAME>
import {
    GITHUB_ORGANIZATION_PROJECT_VIEW_BY_URL,
    GITHUB_ORGANIZATION_BY_NAME,
    GITHUB_REPOSITORY_BY_OWNER_NAME_AND_REPOSITORY_NAME,
    GITHUB_PROJECT_BY_OWNER_NAME_AND_REPOSITORY_NAME_AND_PROJECT_NAME
} from "./github_graphql_queries";
import { fetchGithubDataUsingGraphql } from "./github_functions";
const GITHUB_PAT = process.env.GITHUB_PAT;

const GITHUB_ORGANIZATION_PROJECT_PARAMS = {
    organization_name: t.String(),
    project_id: t.Numeric()
} as const;

function validateView(view: number | undefined) {
    if (view === undefined) {
        return -1;
    }

    return view;
}

export const GITHUB_URL = new Elysia({ prefix: '/url' })
    .group("/orgs/:organization_name/projects/:project_id", (app) => app
        .get('', async ({ params: { organization_name, project_id }, set }) => {
            const response = await fetchGithubDataUsingGraphql<{ organization: Organization }>(
                GITHUB_ORGANIZATION_PROJECT_VIEW_BY_URL(organization_name, project_id, -1),
                GITHUB_PAT,
                set
            );

            return JSON.stringify(response, null, 2);
        }, {
            params: t.Object(GITHUB_ORGANIZATION_PROJECT_PARAMS)
        })
        .get('/views/:project_view', async ({ params: { organization_name, project_id, project_view }, set }) => {
            const response = await fetchGithubDataUsingGraphql<{ organization: Organization }>(
                GITHUB_ORGANIZATION_PROJECT_VIEW_BY_URL(organization_name, project_id, validateView(project_view)),
                GITHUB_PAT,
                set
            );

            return JSON.stringify(response, null, 2);
        }, {
            params: t.Object({
                ...GITHUB_ORGANIZATION_PROJECT_PARAMS,
                project_view: t.Optional(t.Numeric({
                    min: 0
                }))
            })
        })
    );

export const GITHUB_ORGANIZATION = new Elysia({ prefix: '/organization' })
    .group("/:organization_name", (app) => app
        .get('', async ({ params: { organization_name }, set }) => {
            const response = await fetchGithubDataUsingGraphql<{ organization: Organization }>(
                GITHUB_ORGANIZATION_BY_NAME(organization_name),
                GITHUB_PAT,
                set
            );

            return JSON.stringify(response, null, 2);
        }, {
            params: t.Object({
                organization_name: t.String()
            })
        })
        .group("/repository/:repository_name", (app) => app
            .get('', async (
                { params: { organization_name, repository_name }, set }) => {
                const response = await fetchGithubDataUsingGraphql<{ organization: Repository }>(
                    GITHUB_REPOSITORY_BY_OWNER_NAME_AND_REPOSITORY_NAME(organization_name, repository_name),
                    GITHUB_PAT,
                    set
                );

                return JSON.stringify(response, null, 2);
            }, {
                params: t.Object({
                    organization_name: t.String(),
                    repository_name: t.String()
                })
            })
            .get('/project/:project_name', async (
                { query, params: { organization_name, repository_name, project_name }, set }) => {
                const response = await fetchGithubDataUsingGraphql<{ repository: Repository }>(
                    GITHUB_PROJECT_BY_OWNER_NAME_AND_REPOSITORY_NAME_AND_PROJECT_NAME(organization_name, repository_name, project_name, validateView(query.view)),
                    GITHUB_PAT,
                    set
                );

                return JSON.stringify(response, null, 2);
            }, {
                query: t.Object({
                    view: t.Optional(t.Numeric({
                        min: 0
                    }))
                }),
                params: t.Object({
                    organization_name: t.String(),
                    repository_name: t.String(),
                    project_name: t.String()
                })
            })
        )
    );
