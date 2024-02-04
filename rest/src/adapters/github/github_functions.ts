import { GraphqlResponseError } from "@octokit/graphql"; // Testing GraphQL Queries: https://docs.github.com/en/graphql/overview/explorer
import { ParseError, NotFoundError, InternalServerError, Context } from "elysia"; // https://elysiajs.com/introduction.html
import { Octokit } from "octokit"; // { App } // https://github.com/octokit/octokit.js
import { GraphqlResponse, GraphqlResponseErrorCode } from "./github_types";

export async function fetchGithubDataUsingGraphql<T>(graphqlInput: string, auth: string | undefined, set: Context["set"]): Promise<GraphqlResponse<T>> {
    set.headers = { "Content-Type": "application/json" };

    try {
        const octokit = new Octokit({ auth });
        const result = await octokit.graphql<T>(graphqlInput);

        return { success: true, data: result };
    } catch (error: any) { // don't catch ValidationError!
        if (error instanceof GraphqlResponseError) {
            // console.log(error); // .response

            set.status = error.errors?.[0].type === GraphqlResponseErrorCode.NOT_FOUND ? 404 : 500;
            return { success: false, error: error.message, cause: error.cause, path: error.errors?.[0].path, type: error.errors?.[0].type };
        } else if (error instanceof InternalServerError) {
            set.status = error.status;
            return { success: false, error: error.status  };
        } else if (error instanceof ParseError) {
            set.status = error.status;
            return { success: false, error: error.status  };
        } else if (error instanceof NotFoundError) {
            set.status = error.status;
            return { success: false, error: error.status  };
        } else {
            set.status = error?.status ?? 500;
            return { success: false, error: error?.status  };
        }
    }
}

export function validateViewParameter(view: number | undefined) {
    if (view === undefined) {
        return -1;
    }

    return view;
}

export function isValidEnumArray(array: string[], enumValues: string[]): boolean {
    for (let i = 0; i < array.length; i++) {
        if (!enumValues.includes(array[i])) {
            return false;
        }
    }
    return true;
}
