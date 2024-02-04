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

export const GITHUB_URL = new Elysia({ prefix: '/url' })
    .get('/orgs/:organization_name/projects/:project_id/views/:project_view', async (
        { params: { organization_name, project_id, project_view }, set }) => {
        const project_view_id = project_view && project_view != "%7Bproject_view%7D" ? project_view : "-1";
        const response = await fetchGithubDataUsingGraphql<{ organization: Organization }>(
            GITHUB_ORGANIZATION_PROJECT_VIEW_BY_URL(organization_name, project_id, project_view_id),
            GITHUB_PAT,
            set
        );

        return JSON.stringify(response, null, 2);
    }, {
        params: t.Object({
            organization_name: t.String(),
            project_id: t.String(),
            project_view: t.Optional(t.String())
        })
    });

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
                    GITHUB_PROJECT_BY_OWNER_NAME_AND_REPOSITORY_NAME_AND_PROJECT_NAME(organization_name, repository_name, project_name, query.view ?? "-1"),
                    GITHUB_PAT,
                    set
                );

                return JSON.stringify(response, null, 2);
            }, {
                params: t.Object({
                    organization_name: t.String(),
                    repository_name: t.String(),
                    project_name: t.String()
                })
            })
        )
    );
