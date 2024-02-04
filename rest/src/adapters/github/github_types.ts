import { ElysiaErrors } from "elysia/dist/error";

export interface GraphqlResponse<T> {
    error?: any;
    success?: boolean;
    data?: T;

    // ElysiaErrors
    server?: ElysiaErrors

    // GraphqlResponseError
    cause?: unknown;
    path?: [any];
    type?: string | undefined;
}

// in type GraphQlQueryResponse<ResponseData> of @octokit/graphql (has string as type...)
export enum GraphqlResponseErrorCode {
    NOT_FOUND = "NOT_FOUND",
}

export enum GRAMMATICAL_NUMBER {
    SINGULAR = 1,
    PLURAL = 0,
    DEFAULT = -1
}
