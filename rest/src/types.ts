import { ElysiaErrors } from "elysia/dist/error";

export interface GraphqlResponse<T> {
    success?: boolean;
    data?: T;
    server?: ElysiaErrors
    error?: any;
}

export enum GRAMMATICAL_NUMBER {
    SINGULAR = 1,
    PLURAL = 0,
    DEFAULT = -1
}