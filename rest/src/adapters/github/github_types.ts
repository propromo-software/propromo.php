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

export enum GITHUB_REPOSITORY_SCOPES {
    COUNT = "count",
    INFO = "info",
    LICENSE = "license",
    VULNERABILITIES = "vulnerabilities",
    TOPICS = "topics",
    LABELS = "labels",
    RELEASES = "releases",
    DEPLOYMENTS = "deployments",
    MILESTONES = "milestones",
    ISSUES = "issues",
    ALL = "all"
}

export enum GITHUB_PROJECT_SCOPES {
    INFO = "root",
    REPOSITORIES_LINKED = "repositories"
}

export type GITHUB_PROJECT_INPUT_SCOPES = GITHUB_PROJECT_SCOPES[] | {project_scopes: GITHUB_PROJECT_SCOPES[], repository_scopes: null | GITHUB_REPOSITORY_SCOPES[]};
