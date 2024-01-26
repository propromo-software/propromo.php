import { GraphqlResponseError } from "@octokit/graphql"; // Testing GraphQL Queries: https://docs.github.com/en/graphql/overview/explorer
import { ParseError, NotFoundError, InternalServerError } from "elysia"; // https://elysiajs.com/introduction.html
import { Octokit } from "octokit"; // { App } // https://github.com/octokit/octokit.js
import { GraphqlResponse } from "./types";

export async function fetchGraphql<T>(graphqlInput: string, auth: string | undefined): Promise<GraphqlResponse<T>> {
    try {
        const octokit = new Octokit({ auth });
        const result = await octokit.graphql<T>(graphqlInput);
        return { success: true, data: result };
    } catch (error) { // don't catch ValidationError!
        if (error instanceof GraphqlResponseError) {
            console.log(error); // .response

            return { success: false, error: error.message };
        } else if (error instanceof InternalServerError) {
            return { success: false, error: error.code  };
        } else if (error instanceof ParseError) {
            return { success: false, error: error.code  };
        } else if (error instanceof NotFoundError) {
            return { success: false, error: error.code  };
        } else {
            return { success: false, error: error };
        }
    }
}
