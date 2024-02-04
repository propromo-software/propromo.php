import { GRAMMATICAL_NUMBER } from "./github_types";

const GITHUB_REPOSITORY = function (amount: GRAMMATICAL_NUMBER, owner?: string, name?: string) {
    let repositoryQueryStart = `
        repositories(first: 10) {
            totalCount
            nodes {
        `;
    let repositoryQueryEnd = `}`;
    if (amount === GRAMMATICAL_NUMBER.SINGULAR && owner && name) {
        repositoryQueryStart = `repository(owner: "${owner}", name: "${name}") {`; 
        repositoryQueryEnd = ``;
    }

    const ISSUE_NODES = `
    nodes {
        title
        bodyUrl
        createdAt
        updatedAt
        url
        closedAt
        body
        lastEditedAt
        milestone {
            createdAt
            closedAt
            description
            dueOn
            progressPercentage
            title
            updatedAt
        }
        assignees(first: 10) {
            nodes {
                avatarUrl
                email
                login
                name
                pronouns
                url
                websiteUrl
            }
        }
        labels(first: 10) {
            nodes {
                url
                name
                color
                createdAt
                updatedAt
                description
                isDefault
            }
        }
    # comments
    }`;

    return `
    ${repositoryQueryStart}
            name
            description
            updatedAt
            createdAt
            isArchived
            isPrivate
            isTemplate
            
            resourcePath
            homepageUrl
            sshUrl
            projectsUrl
            
            licenseInfo {
                url
                spdxId
                name
                nickname
                description
                conditions {
                    description
                    key
                    label
                }
            }
            
            vulnerabilityAlerts(first: 10) {
                nodes {
                    createdAt
                    fixedAt
                    dependencyScope
                    securityVulnerability {
                        vulnerableVersionRange
                        updatedAt
                        advisory {
                            classification
                            description
                            publishedAt
                            summary
                            updatedAt
                            updatedAt
                        }
                        
                        firstPatchedVersion {
                            identifier
                        }
                        package {
                            ecosystem
                            name
                        }
                    }
                    dependabotUpdate {
                        error {
                            title
                            body
                            errorType
                        }
                    }
                }
            }
            repositoryTopics(first: 10) {
                nodes {
                    topic {
                        name
                    }
                    url
                }
            }
            releases(first: 10) {
                nodes {
                    name
                    createdAt
                    description
                    isDraft
                    isLatest
                    isPrerelease
                    name
                    tagName
                    updatedAt
                    url
                    tag {
                        name
                    }
                    tagCommit {
                        additions
                        deletions
                        authoredDate
                        changedFilesIfAvailable
                        author {
                            avatarUrl
                            email
                            name
                        }
                    }
                    author {
                        avatarUrl
                        email
                        login
                        name
                        pronouns
                        url
                        websiteUrl
                    }
                    releaseAssets(first: 10) {
                        nodes {
                            contentType
                            createdAt
                            downloadCount
                            name
                            size
                            updatedAt
                        }
                    }
                }
            }
            milestones(first: 10) {
                nodes {
                    createdAt
                    closedAt
                    description
                    dueOn
                    progressPercentage
                    title
                    updatedAt
                    open_issues: issues(first: 50, states: [OPEN]) {
                        totalCount
                        ${ISSUE_NODES}
                    }
                    closed_issues: issues(first: 50, states: [CLOSED]) {
                        totalCount
                        ${ISSUE_NODES}
                    }
                }
            }
            mentionableUsers(first: 10) {
                nodes {
                    avatarUrl
                    email
                    login
                    name
                    pronouns
                    url
                    websiteUrl
                }
            }
            primaryLanguage {
                color
                name
            }
            languages(first: 10) {
                totalCount
                nodes {
                    color
                    name
                }
            }
            labels(first: 10) {
                totalCount
                nodes {
                    color
                    createdAt
                    description
                    name
                    url
                }
            }
            deployments(first:10) {
                nodes {
                updatedAt
                createdAt
                updatedAt
                description
                environment
                task
                latestStatus {
                    createdAt
                    updatedAt
                    description
                    logUrl
                    environmentUrl
                    state
                    deployment {
                        createdAt
                        description
                        commit {
                            additions
                            deletions
                            authoredDate
                            changedFilesIfAvailable
                            author {
                                avatarUrl
                                email
                                name
                            }
                        }
                    }
                }
                statuses(first: 10) {
                    nodes {
                        createdAt
                        updatedAt
                        description
                        logUrl
                        environmentUrl
                        state
                        deployment {
                            createdAt
                            description
                            commit {
                                additions
                                deletions
                                authoredDate
                                changedFilesIfAvailable
                                author {
                                    avatarUrl
                                    email
                                    name
                                }
                            }
                        }
                    }
                }
                }
            }
            collaborators(first: 10) {
                nodes {
                    avatarUrl
                    email
                    login
                    name
                    pronouns
                    url
                    websiteUrl
                }
            }
        ${repositoryQueryEnd}
    }`;
}

const GITHUB_PROJECT = function (amount: GRAMMATICAL_NUMBER, name?: string | number, view?: number) {
    let projectQueryParameters = amount === GRAMMATICAL_NUMBER.SINGULAR ? `query: "${name}", first: 1` : `first: 100`;
    let projectV2 = `projectsV2(${projectQueryParameters})`;
    let repositoryQueryStart = `
    ${projectV2} {
        totalCount
        nodes {
    `;
    let repositoryQueryEnd = `}`;

    if (amount === GRAMMATICAL_NUMBER.DEFAULT) {
        projectQueryParameters = `number: ${name}`;
        projectV2 = `projectV2(${projectQueryParameters})`;
        repositoryQueryStart = `
        ${projectV2} {
        `;
        repositoryQueryEnd = ``;
    }

    let viewQuery = `
    view(number: ${view}) {
        createdAt
        updatedAt
        name
        filter
        layout
    }`;

    if (view === -1) {
        view = 10;
        
        viewQuery = `
        views(first: ${view}) {
            totalCount
            nodes {
                createdAt
                updatedAt
                name
                filter
                layout
            }
        }`;
    }

    return `
    ${repositoryQueryStart}
            title
            shortDescription
            url
            public
            createdAt
            updatedAt
            closedAt
            readme
            
            ${viewQuery}

            items(first: 100) {
                totalCount
                nodes {
                    createdAt
                      updatedAt
                      isArchived
                      type
                }
            }

            teams(first: 4) {
                totalCount
                nodes {
                    avatarUrl
                    combinedSlug
                    createdAt
                    updatedAt
                    description
                    membersUrl
                    name
                    slug
                    members(first: 32) {
                        nodes {
                        avatarUrl
                        email
                        login
                        name
                        pronouns
                        url
                        websiteUrl
                        }
                    }
                }
            }
            
            ${GITHUB_REPOSITORY(GRAMMATICAL_NUMBER.PLURAL)}
        ${repositoryQueryEnd}
    }`;
}

// /orgs/<ORGANIZATION_NAME>/projects/<PROJECT_NUMBER>?/views/<VIEW_NUMBER>
export const GITHUB_ORGANIZATION_PROJECT_VIEW_BY_URL = function (organization_name: string, project_id: number, project_view: number) {
    // every projects items are stored in the repositories it is connected to
    return `{
        organization(login: "${organization_name}") {
            ${GITHUB_PROJECT(GRAMMATICAL_NUMBER.DEFAULT, project_id, project_view)}
        }
    }`
}

// /users/<USER_NAME>/projects/<PROJECT_NUMBER>?/views/<VIEW_NUMBER>
export const GITHUB_USER_PROJECT_VIEW_BY_URL = function (user_name: string, project_id: number, project_view: number) {
    // every projects items are stored in the repositories it is connected to
    return `{
        user(login: "${user_name}") {
            ${GITHUB_PROJECT(GRAMMATICAL_NUMBER.DEFAULT, project_id, project_view)}
        }
    }`
}

export const GITHUB_ORGANIZATION_BY_NAME = function (organization_name: string) { 
    return `{
        organization(login: "${organization_name}") {
            login
            name
            description
            email
            url
            
            websiteUrl
            avatarUrl
            
            createdAt
            updatedAt
            isVerified
            location
            
            announcement
            
            packages(first: 10) {
                totalCount
                nodes {
                    name
                    packageType
                    latestVersion {
                        files(first: 10) {
                            nodes {
                                sha256
                                name
                                size
                                updatedAt
                                url
                            }
                        }
                        platform
                        preRelease
                        readme
                        version
                    }
                }
            }

            ${GITHUB_REPOSITORY(GRAMMATICAL_NUMBER.PLURAL)}
            
            teamsUrl
            teams(first: 10) {
                totalCount
                nodes {
                    avatarUrl
                    combinedSlug
                    createdAt
                    updatedAt
                    description
                    membersUrl
                    name
                    slug
                    members(first: 10) {
                        nodes {
                        avatarUrl
                        email
                        login
                        name
                        pronouns
                        url
                        websiteUrl
                        }
                    }
                }
            }
            
            projectsUrl
            projectsV2(first: 100) {
                totalCount
                nodes {
                    number
                    title
                    url
                    createdAt
                    closedAt
                    public
                    readme
                    shortDescription
                    url
                }
            }
        }
    }`;
}

export const GITHUB_REPOSITORY_BY_OWNER_NAME_AND_REPOSITORY_NAME = function (organization_name: string, repository_name: string) {
    return `{
        ${GITHUB_REPOSITORY(GRAMMATICAL_NUMBER.SINGULAR, organization_name, repository_name)}
    }`
}

export const GITHUB_PROJECT_BY_OWNER_NAME_AND_REPOSITORY_NAME_AND_PROJECT_NAME = function (organization_name: string, repository_name: string, project_name: string, view: number) {
    // every project has a repository for its items
    return `{
        repository(owner: "${organization_name}", name: "${repository_name}") {
            ${GITHUB_PROJECT(GRAMMATICAL_NUMBER.SINGULAR, project_name, view)}
        }
    }`
}
