export const license = `
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
`;

export const vulnerabilities = `
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
`;

export const topics = `
repositoryTopics(first: 10) {
    nodes {
        topic {
            name
        }
        url
    }
}
`;

export const labels = `
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
`;

export const releases = `
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
`;

export const deployments = `
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
`;

export const issues = `
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