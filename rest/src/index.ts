import { Elysia } from "elysia"; // https://elysiajs.com/introduction.html
import { cors } from '@elysiajs/cors'; // https://elysiajs.com/plugins/cors.html#cors-plugin
import { Octokit } from "octokit"; // unused: App // https://github.com/octokit/octokit.js | https://github.com/octokit/types.ts
import 'dotenv/config'; // process.env.<ENV_VAR_NAME>

const GITHUB_PAT = process.env.GITHUB_PAT;
const octokit = new Octokit({ auth: GITHUB_PAT });

const app = new Elysia()
  .use(cors({
    origin: 'https://propromo.duckdns.org'
  }))
  .get("/", () => "Hello Elysia")
  .get('/organization/:organization_name', async ({ params: { organization_name } }) => {
  const {
    organization: organization,
  } = await octokit.graphql(`{
    organization(login: ${"\"" + organization_name + "\""}) {
      name
      description
      url
      teams(first: 10) {
        totalCount
        nodes {
          name
        }
      }
      membersWithRole(first: 10) {
        totalCount
        nodes {
          name
        }
      }
      repositories(first: 10) {
        nodes {
          name
        }
      }
      projectsV2(first: 10) {
        totalCount
        nodes {
          title
        }
      }
      websiteUrl
      email
    }
  }`);

  return JSON.stringify(organization, null, 2);
  })
  .listen(process.env.PORT || 3000);

console.log(
  `🦊 Elysia is running at ${app.server?.hostname}:${app.server?.port}`
);

/* Testing query (test on https://docs.github.com/en/graphql/overview/explorer)
query {
  repository(owner: "propromo-software", name: "propromo") {
    projectV2(number: 1) {
      title
      shortDescription
      url
      public
      readme
      createdAt
      updatedAt
      items(first: 10) {
        totalCount # total issues
      }
      view(number: 1) {
        name
        filter
      }
      creator {
        login
      }
      repositories(first: 10) {
        totalCount
        nodes {
          name
          issues(first: 50, states: [OPEN]) {
            totalCount
            nodes {
              title
            }
          }
        }
      }
    }
    projectsV2(first: 10) {
      totalCount
      nodes {
        number
        title
        shortDescription
        views {
          totalCount
        }
      }
    }
  }
}
*/